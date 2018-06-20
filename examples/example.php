<?php

include_once(__DIR__ . '/../vendor/autoload.php');

$reader = new \edwrodrig\genbank_reader\GenbankReader("GCF_000008525.1_ASM852v1_genomic.gbff");
echo $reader->getHeader()->getDefinition();
echo "\n";
echo $reader->getFeatures()->getSource()->getLocation()->getOriginalText();
