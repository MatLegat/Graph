<?php
    /*
    Functions to test my implementation of a graph structure in php.
    Author: Matteus Legat
    Created on: November, 8, 2015
    */

    header("Content-type: text/html; charset=utf-8");

    /*
    Pega entrada na linha de comando.
    */
    function getInput() {
        $input_file = fopen("php://stdin","r");
        $input_line = fgets($input_file);
        fclose($input_file);
        return $input_line;
    }

    /*
    Testes realizados com a estrutura.
    */
    function test1() {
        for($k = 0; $k < 3; $k++) {
            $directed = (bool) $k;
            $graph = new Graph($directed);
            $graph->createVertex('POTATO');
            $graph->createVertex('ORANGE');
            $graph->createVertex('POTATO');
            $graph->connect($graph->getVertexById(2), $graph->getVertexById(3), 12);
            $graph->getVertexByLabel('ORANGE')->mark("TAG 1");
            $graph->connect($graph->getVertexById(3), $graph->getVertexById(0));
            $graph->disconnect($graph->getVertexById(0), $graph->getVertexByLabel('POTATO'));
            var_dump($graph->isConnected($graph->getVertexById(3), $graph->getVertexById(0)));
            // var_dump($graph->getEdge($graph->getVertexById(2), $graph->getVertexById(3)));
            if ($k == 2) {
                $graph->removeVertex($graph->getVertexById(3));
                $graph->connect($graph->getVertexById(0), $graph->getVertexById(1));
            }
            var_dump($graph);
            echo "::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::\n";
        }
    }

?>
