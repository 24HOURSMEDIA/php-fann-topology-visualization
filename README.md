# FANN neural network visualizations

This component visualizes a FANN neural network using D3JS.
The visualization shows nodes and their connections to eachother.

Negative connections are shown in red, positive in green. The thickness and brightness
of the connection is an indicator of it's strength.

To create a visualization, you can configure a visitor that can visit the FANN topology:

```php
use T4\Fann\Topology\Core\Topology;
use T4\Fann\Topology\Core\Neuron;
use T4\Fann\Topology\Visualization\D3J\D3JsNeuronVisitor;

$ann = ....; // your fann neural network resource
$topology = Topology::createFromFann($ann);

$visitor = D3JsNeuronVisitor();

// configure visitor here; here the node is given a name
$visitor->setNamingCallback(function(Neuron $n) {
    return 'node #' . $n;
});

// visit and get the collected data for configuring d3js
foreach ($topology->getNeurons() as $k => $neuron) {
    $neuron->accept($visitor);
}
$data = $visitor->getJsonData();

// @TODO: include in a template

```