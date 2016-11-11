# Open My Graph
a quick open graph parser

## usage

$parser = new Omg\Parser;

$tab = $parser->parse('http://www.enkulte.fr');

echo $tab['image']; //http://www.enkulte.fr/enkulte.jpg
