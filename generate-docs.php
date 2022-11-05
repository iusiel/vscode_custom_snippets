<?php
$generatedHTML = "";

$globalCodeSnippets = file_get_contents("custom_snippets/global.code-snippets");
$globalCodeSnippets = preg_replace("#(/\*([^*]|[\r\n]|(\*+([^*/]|[\r\n])))*\*+/)|([\s\t]//.*)|(^//.*)#", '', $globalCodeSnippets); // remove all comments
$globalCodeSnippets = json_decode($globalCodeSnippets);

foreach ($globalCodeSnippets as $index => $snippet) {
    $generatedHTML .= sprintf('
    <section style="border-bottom: 1px solid #000;padding-bottom: 30px;">
        <h2>%s</h2>
        <h3>%s</h3>
        <p>
        <mark><strong>%s</strong></mark> transforms to
        </p>
        <pre
        onclick="copyCode(\'%s\')"
        title="Click to copy to clipboard"
        ><code class="language-html">%s</code></pre>
    </section>
    ', $index, $snippet->description, $snippet->prefix, base64_encode(implode(PHP_EOL, $snippet->body)), htmlentities(implode(PHP_EOL, $snippet->body)));
}



$it = new FilesystemIterator("custom_snippets");
foreach ($it as $fileinfo) {
    if ($fileinfo->getFileInfo()->getExtension() === 'json') {
        $fileContents = file_get_contents($fileinfo->getPathname());
        $fileContents = preg_replace("#(/\*([^*]|[\r\n]|(\*+([^*/]|[\r\n])))*\*+/)|([\s\t]//.*)|(^//.*)#", '', $fileContents); // remove all comments
        $fileContents = json_decode($fileContents);

        foreach ($fileContents as $index => $snippet) {
            $generatedHTML .= sprintf('
            <section style="border-bottom: 1px solid #000;padding-bottom: 30px;">
                <h2>%s</h2>
                <h3>%s</h3>
                <p>
                <mark><strong>%s</strong></mark> transforms to
                </p>
                <pre
                onclick="copyCode(\'%s\')"
                title="Click to copy to clipboard"
                ><code class="language-html">%s</code></pre>
            </section>
            ', $index, $snippet->description, $snippet->prefix, base64_encode(implode(PHP_EOL, $snippet->body)), htmlentities(implode(PHP_EOL, $snippet->body)));
        }

    } 
}



// echo PHP_EOL;

$htmlInitialContents = file_get_contents('base.html');
$replacedContents = str_replace('[slot]', $generatedHTML, $htmlInitialContents);
$replacedContents = str_replace('\$', '$', $replacedContents);

file_put_contents("docs/index.html", $replacedContents);