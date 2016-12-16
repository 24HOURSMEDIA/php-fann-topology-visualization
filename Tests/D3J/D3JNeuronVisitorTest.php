<?php
/**
 * Date: 15/12/2016
 */

namespace T4\Fann\Topology\Visualization\Tests\D3J;


use T4\Fann\Topology\Visualization\D3J\D3JsNeuronVisitor;
use T4\Fann\Topology\Visualization\Tests\AbstractVisualizationTestCase;
use T4\Fann\Topology\Core\Topology;

class D3JNeuronVisitorTestCase extends AbstractVisualizationTestCase
{

    function testVisitNeuron() {
        $fann = $this->getXORFann();
        $topology = Topology::createFromFann($fann);
        $this->destroyFann($fann);

        $this->assertGreaterThan(3, $topology->getNeurons());

        $visitor = new D3JsNeuronVisitor();
        foreach ($topology->getNeurons() as $k => $neuron) {
            $neuron->accept($visitor);
        }

        $jsonData = $visitor->getJsonData();

        // more testing..


    }

}