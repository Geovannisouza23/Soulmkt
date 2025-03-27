<?php
class ProductProcessor
{
    private $separator;
    private $file;

    public function __construct($file, $separator)
    {
        $this->file = $file;
        $this->separator = $separator;
    }

    public function process()
    {
        $data = $this->parseCsv();
        $products = $this->filterAndSortProducts($data);
        echo json_encode($products);
    }

    private function parseCsv()
    {
        if (!file_exists($this->file) || !is_readable($this->file)) {
            return [];
        }

        $handle = fopen($this->file, "r");
        $rows = [];

        while (($row = fgetcsv($handle, 1000, $this->separator)) !== false) {
            $rows[] = $row;
        }

        fclose($handle);
        return $rows;
    }

    private function filterAndSortProducts($data)
    {
        if (empty($data) || !isset($data[0])) {
            return [];
        }

        $products = [];
        $header = array_map('strtolower', $data[0]);

        // Identificar colunas
        $nameCol = array_search('nome', $header);
        $codeCol = array_search('codigo', $header);
        $priceCol = array_search('preco', $header);

        if ($nameCol === false || $codeCol === false || $priceCol === false) {
            return []; // Retorna vazio caso as colunas essenciais não sejam encontradas
        }

        // Processar produtos
        foreach ($data as $index => $row) {
            if ($index === 0) continue; // Ignorar cabeçalho

            $name = isset($row[$nameCol]) ? trim($row[$nameCol]) : '';
            $code = isset($row[$codeCol]) ? trim($row[$codeCol]) : '';
            $price = isset($row[$priceCol]) ? floatval($row[$priceCol]) : 0;

            // Ignorar produtos sem nome ou código válido
            if (!$name || !$code) continue;

            $isNegativePrice = $price < 0;
            $hasEvenNumber = $this->hasEvenNumberInCode($code);

            $products[] = [
                'name' => $name,
                'code' => $code,
                'price' => $price,
                'isNegativePrice' => $isNegativePrice,
                'hasEvenNumber' => $hasEvenNumber
            ];
        }

        // Ordenar produtos pelo nome
        usort($products, function ($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        return $products;
    }

    private function hasEvenNumberInCode($code)
    {
        preg_match_all('/\d/', $code, $matches);
        foreach ($matches[0] as $digit) {
            if (intval($digit) % 2 === 0) {
                return true;
            }
        }
        return false;
    }
}

// Verificar upload e processar arquivo
if (isset($_FILES['fileInput']) && $_FILES['fileInput']['error'] === UPLOAD_ERR_OK) {
    $separator = $_POST['separator'] ?? ',';
    $file = $_FILES['fileInput']['tmp_name'];
    $processor = new ProductProcessor($file, $separator);
    $processor->process();
} else {
    echo json_encode(['error' => 'Nenhum arquivo foi enviado ou ocorreu um erro no upload.']);
}
?>
