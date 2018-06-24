<?php

namespace AppBundle\Controller;

use AppBundle\Contract\CalculatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @return Response
     */
    public function indexAction(): Response
    {
        /** @var CalculatorInterface $timePunchesCalculator */
        $timePunchesCalculator = $this->get('7shifts_time_punches_calculator');

        return $this->render('default/index.html.twig', [
            'data' => $timePunchesCalculator->calculate()
        ]);
    }
}
