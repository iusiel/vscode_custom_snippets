<?php

$generatedHTML = "";

$globalCodeSnippets = file_get_contents("custom_snippets/global.code-snippets");
$globalCodeSnippets = preg_replace("#(/\*([^*]|[\r\n]|(\*+([^*/]|[\r\n])))*\*+/)|([\s\t]//.*)|(^//.*)#", '', $globalCodeSnippets); // remove all comments
$globalCodeSnippets = json_decode($globalCodeSnippets);

foreach ($globalCodeSnippets as $index => $snippet) {
    $generatedHTML .= sprintf('
    <section>
        <h2>%s</h2>
        <h3>%s</h3>
        <p>
        <mark><strong>%s</strong></mark> transforms to
        </p>
        <pre
        title="Click to copy to clipboard"
        ><code class="language-html">%s</code></pre>
    </section>
    ', $index, $snippet->description, $snippet->prefix, htmlentities(implode(PHP_EOL, $snippet->body)));
}
var_dump($generatedHTML);

$htmlInitialContents = file_get_contents('base.html');
$replacedContents = str_replace('[slot]', $generatedHTML, $htmlInitialContents);

file_put_contents("docs/index.html", $replacedContents);




// $it = new FilesystemIterator("custom_snippets");
// foreach ($it as $fileinfo) {
//     if ($fileinfo->getFileInfo()->getExtension() === 'json') {
//         $fileContents = file_get_contents($fileinfo->getPathname());
//         $fileContents = preg_replace("#(/\*([^*]|[\r\n]|(\*+([^*/]|[\r\n])))*\*+/)|([\s\t]//.*)|(^//.*)#", '', $fileContents); // remove all comments
//         $fileContents = json_decode($fileContents);
//         var_dump($fileContents);
//     }
// }



// echo PHP_EOL;