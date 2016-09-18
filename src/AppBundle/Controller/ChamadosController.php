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

        $pedido = $request->get('pedido');
        $pedido = null;
        // get Pedido
        if (is_null($pedido) || empty($pedido)) {
            return $response->setData([
                'status'   => 0,
                'mensagem' => 'Este pedido não existe, verifique se foi digitado corretamente.',
                'cor'      => 'red',
            ]);
        }

        // Salvar Cliente

        // Salvar Chamado
        return $response->setData([
            'status'   => 1,
            'mensagem' => 'Chamado cadastrado com suceso!',
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
     * @Route("/relatorio", name="chamados_relatorio")
     */
    public function relatorioAction(Request $request, $page = 1)
    {
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
            $chamados = [
                [
                    'cliente' => 'ABC',
                    'email' => 'abc@abc.com',
                    'pedido' => 123,
                    'id' => 1,
                    'titulo' => 'ABC Título',
                    'observacao' => 'ABC Observação'
                ],
                [
                    'cliente' => 'DEF',
                    'email' => 'def@def.com',
                    'pedido' => 456,
                    'id' => 2,
                    'titulo' => 'DEF Título',
                    'observacao' => 'DEF Observação'
                ],
                [
                    'cliente' => 'GHI',
                    'email' => 'ghi@ghi.com',
                    'pedido' => 789,
                    'id' => 3,
                    'titulo' => 'GHI Título',
                    'observacao' => 'GHI Observação'
                ]
            ];
        }

        return $this->render('chamados/relatorio.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
            'filtrar'  => !empty($filtros),
            'filtros'  => $filtros,
            'chamados' => $chamados,
        ]);
    }
}
