<?php
/**
 * Date: 14/12/2016
 */

namespace T4\Fann\Topology\Visualization\Tests\Arbor;


use T4\Fann\Topology\Core\Topology;
use T4\Fann\Topology\Visualization\Arbor\ArborNeuronVisitor;
use T4\Fann\Topology\Visualization\Tests\AbstractVisualizationTestCase;

/**
 * Class ArbonNeuronVisitorTestCase
 * phpunit src/T4/Fann/Topology/Visualization/Tests/Arbor/ArbonNeuronVisitorTestCase
 */
class ArbonNeuronVisitorTestCase extends AbstractVisualizationTestCase
{

    function testVisitNeuron() {
        $fann = $this->getXORFann();

        $topology = Topology::createFromFann($fann);
        $this->assertGreaterThan(3, $topology->getNeurons());

        $visitor = new ArborNeuronVisitor();
        foreach ($topology->getNeurons() as $k => $neuron) {
            $neuron->accept($visitor);
        }

        $codeLines = $visitor->getAllLines();
        echo PHP_EOL . (implode(PHP_EOL, $codeLines));

        $this->destroyFann($fann);
    }

}