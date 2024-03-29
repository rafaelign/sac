<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Csrf\CsrfProvider;

class ChamadosController extends Controller
{
    /**
     * @Route("/", name="chamados")
     * @Method({"GET", "HEAD"})
     */
    public function indexAction(Request $request)
    {
        return $this->render('chamados/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ]);
    }

    /**
     * @Route("/seed", name="chamados_seed")
     * @Method({"GET", "HEAD"})
     */
    public function seedAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Pedido');
        $pedidos = $repository->findAll();
        
        if (empty($pedidos)) {
            for ($n = 0;$n < 10;$n++) {
                $pedidos[] = $repository->novo($this->getDoctrine()->getManager(), [ 
                    'numero' => ($n + 1) * 10
                ]);
            }
        }

        return $this->render('chamados/seed.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
            'pedidos'  => $pedidos,
        ]);
    }

    /**
     * @Route("/novo", name="chamados_novo")
     * @Method({"GET", "HEAD"})
     */
    public function novoAction(Request $request)
    {
        return $this->render('chamados/novo.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ]);
    }

    /**
     * @Route("/salvar", name="chamados_salvar")
     * @Method({"POST"})
     */
    public function salvarAction(Request $request)
    {
        $response = new JsonResponse();
        $em = $this->getDoctrine()->getManager();
        $pedidoRepository = $this->getDoctrine()->getRepository('AppBundle:Pedido');
        $clienteRepository = $this->getDoctrine()->getRepository('AppBundle:Cliente');
        $chamadoRepository = $this->getDoctrine()->getRepository('AppBundle:Chamado');

        $pedidoNumero = $request->get('pedido');
        $pedido = $pedidoRepository->findOneByNumero($pedidoNumero);
        // get Pedido
        if (is_null($pedido) || empty($pedido)) {
            return $response->setData([
                'status'   => 0,
                'mensagem' => 'Este pedido não existe, verifique se foi digitado corretamente.',
                'cor'      => 'red',
            ]);
        }

        // Novo Cliente
        $cliente = $clienteRepository->findOrCreate($em, [
            'nome'  => $request->get('cliente'),
            'email' => $request->get('email'),
        ]);
        
        // Novo Chamado
        $chamado = $chamadoRepository->novo($em, [
            'titulo'     => $request->get('titulo'),
            'observacao' => $request->get('observacao'),
            'cliente'    => $cliente,
            'pedido'     => $pedido,
        ]);
        
        return $response->setData([
            'status'   => 1,
            'mensagem' => 'Chamado #'. $chamado->getId() .' cadastrado com suceso!',
            // 'mensagem' => 'Chamado # cadastrado com suceso!',
            'cor'      => 'green',
        ]);
    }

    /**
     * @Route("/relatorio/limpar", name="chamados_relatorio_limpar")
     * @Method({"GET", "HEAD"})
     */
    public function relatorioLimparAction(Request $request)
    {
        $session = $request->getSession();
        $session->remove('filtros');

        $response = new JsonResponse();
        return $response->setData(1);
    }

    /**
     * @Route("/relatorio/{pagina}", name="chamados_relatorio")
     */
    public function relatorioAction(Request $request, $pagina = 1)
    {
        $limit    = 5;
        $offset   = ($limit * $pagina) - $limit;
        $count    = 0;
        $chamados = [];
        $session  = $request->getSession();
        $filtros  = $session->get('filtros', []);

        if (in_array($request->getMethod(), ["POST"])) {
            if (trim($request->request->get('email')) != '') {
                $filtros['email'] = trim($request->request->get('email'));
            }
            if (trim($request->request->get('pedido')) != '') {
                $filtros['pedido'] = trim($request->request->get('pedido'));
            }

            $session->set('filtros', $filtros);
        }

        // Busca de chamados
        if (!empty($filtros)) {
            $chamadoRepository = $this->getDoctrine()->getRepository('AppBundle:Chamado');
            $clienteRepository = $this->getDoctrine()->getRepository('AppBundle:Cliente');
            $pedidoRepository = $this->getDoctrine()->getRepository('AppBundle:Pedido');
            
            $filtros_ = [];
            if (isset($filtros['email'])) {
                $filtros_['cliente'] = $clienteRepository->findByEmail($filtros['email']);
            } 
            if (isset($filtros['pedido'])) {
                $filtros_['pedido'] = $pedidoRepository->findByNumero($filtros['pedido']);
            } 
            
            $count    = count($chamadoRepository->findBy( $filtros_ ));
            $chamados = $chamadoRepository->findBy( $filtros_, [], $limit, $offset );
        }

        return $this->render('chamados/relatorio.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
            'filtrar'  => !empty($filtros),
            'filtros'  => $filtros,
            'chamados' => $chamados,
            'total'    => $count,
            'total_pg' => ceil($count/$limit),
            'pagina'   => $pagina,
        ]);
    }
}
