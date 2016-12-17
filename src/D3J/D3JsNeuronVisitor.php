<?php
/**
 * Date: 15/12/2016
 */

namespace T4\Fann\Topology\Visualization\D3J;

use T4\Fann\Topology\Core\Layer;
use T4\Fann\Topology\Core\Neuron;
use T4\Fann\Topology\Core\NeuronVisitorInterface;

/**
 * Class D3JsNeuronVisitor
 * Visits neurons and collects data for creating a graph
 */
class D3JsNeuronVisitor implements NeuronVisitorInterface
{

    protected $jsonData = ['nodes' => [], 'links' => []];

    protected $minWeight = 0.001;
    protected $maxWeight = 1;

    protected $canvasSize = [900, 1200];

    /**
     * Optional closure that has a Neuron as input, and returns a name
     * @var
     */
    protected $namingCallback;

    function visitNeuron(Neuron $neuron)
    {
        if ($neuron->getType() == Neuron::TYPE_BIAS) {
            return;
        }

        // get canvas area with margins
        $ofsX = 100;
        $ofsY = 30;
        $paddedW = $this->canvasSize[0] - (2 * $ofsX);
        $paddedH = $this->canvasSize[1] - (2 * $ofsY);


        $spacingX = $paddedW / count($neuron->getLayer()->getNeurons());
        $spacingY = $paddedH / count($neuron->getTopology()->getLayers());

        // assign layer y coords
        $numLayers = count($neuron->getTopology()->getLayers());
        $layerY = (int)(($ofsY + ((0.5 + $neuron->getLayer()->getIndex()) * $spacingY)));
        $layerX = (int)(($ofsX + (0.5+ $neuron->getIndexInLayer()) * $spacingX));

        // neuron
        $neuronId = 'n#' . $neuron->getIndex();
        /*
        switch ($neuron->getLayer()->getType()) {
            case Layer::TYPE_INPUT:
                $group = 2;
                break;
            case Layer::TYPE_OUTPUT:
                $group = 3;
                break;
            default:
                $group = 1;
        }
        */
        $group = $neuron->getLayer()->getIndex();


        if ($this->namingCallback) {
            $cb = $this->namingCallback;
            $name = $cb($neuron);
        } else {
            if ($neuron->getType() != Neuron::TYPE_HIDDEN) {
                $name = 'N' . ($neuron->getIndex() + 1);

            } else {
                $name = '';
            }
        }

        $this->jsonData['nodes'][] = [
            'id' => $neuronId,
            'group' => $group,
            'label' => $name,
            'layer_idx' => $neuron->getLayer()->getIndex(),
            'layer_y' => $layerY,
            'layer_x' => $layerX
        ];

        // connections
        foreach ($neuron->getConnections() as $connection) {
            if (
                abs($connection->getNormalizedWeight()) >= $this->minWeight
            && abs($connection->getNormalizedWeight()) <= $this->maxWeight
            ) {
                $fromId = $this->generateId($connection->getFromNeuron());
                $toId = $this->generateId($connection->getToNeuron());
                $item = [
                    'source' => $fromId,
                    'target' => $toId,
                    'value' => (($connection->getNormalizedWeight() + 1) / 2),
                    'weight' => $connection->getWeight()
                ];
                $this->jsonData['links'][] = $item;
            }
        }

    }

    protected function generateId(Neuron $n)
    {
        return 'n#' . $n->getIndex();
    }

    /**
     * Get the JsonData
     * @return array
     */
    public function getJsonData()
    {
        return $this->jsonData;
    }

    /**
     * Get the MinWeight
     * @return float
     */
    public function getMinWeight()
    {
        return $this->minWeight;
    }

    /**
     * Set the minWeight
     * @param float $minWeight
     */
    public function setMinWeight($minWeight)
    {
        $this->minWeight = $minWeight;

        return $this;
    }

    /**
     * Get the MaxWeight
     * @return int
     */
    public function getMaxWeight()
    {
        return $this->maxWeight;
    }

    /**
     * Set the maxWeight
     * @param int $maxWeight
     */
    public function setMaxWeight($maxWeight)
    {
        $this->maxWeight = $maxWeight;

        return $this;
    }

    /**
     * Set the namingCallback
     * @param mixed $namingCallback
     */
    public function setNamingCallback($namingCallback)
    {
        $this->namingCallback = $namingCallback;

        return $this;
    }

    /**
     * Get the CanvasSize
     * @return array
     */
    public function getCanvasSize()
    {
        return $this->canvasSize;
    }

    /**
     * Set the canvasSize
     * @param array $canvasSize
     * @return $this
     */
    public function setCanvasSize($width, $height)
    {
        $this->canvasSize = [$width, $height];

        return $this;
    }


}