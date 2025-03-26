<?php

require_once __DIR__ . '/../server/upload.php'; 
use PHPUnit\Framework\TestCase;

class ProductProcessorTest extends TestCase
{
    private $testFile;

    protected function setUp(): void
    {
        // Criar um arquivo CSV de teste temporário
        $this->testFile = tempnam(sys_get_temp_dir(), 'test_') . '.csv';
    }

    protected function tearDown(): void
    {
        // Remover o arquivo de teste após os testes
        unlink($this->testFile);
    }

    public function testMissingColumn()
    {
        // Criar um CSV sem a coluna 'Codigo'
        file_put_contents($this->testFile, "Nome,Preco\nProduto1,10.5\nProduto2,-5.5\n");

        $processor = new ProductProcessor($this->testFile, ',');

        $data = $processor->parseCsv();
        $result = $processor->filterAndSortProducts($data);

        // Nenhum produto deve ser processado, pois a coluna 'Codigo' está ausente
        $this->assertCount(0, $result);
    }

    public function testMissingNameColumn()
    {
        // Criar um CSV sem a coluna 'Nome'
        file_put_contents($this->testFile, "Codigo,Preco\n123,10.5\n456,-5.5\n");

        $processor = new ProductProcessor($this->testFile, ',');

        $data = $processor->parseCsv();
        $result = $processor->filterAndSortProducts($data);

        // Nenhum produto deve ser processado, pois a coluna 'Nome' está ausente
        $this->assertCount(0, $result);
    }

    public function testProductWithMissingName()
    {
        // Criar um CSV com produto sem nome
        file_put_contents($this->testFile, "Nome,Codigo,Preco\n,123,10.5\nProduto2,456,-5.5\n");

        $processor = new ProductProcessor($this->testFile, ',');

        $data = $processor->parseCsv();
        $result = $processor->filterAndSortProducts($data);

        // Apenas o produto com nome válido deve ser processado
        $this->assertCount(1, $result);
        $this->assertEquals('Produto2', $result[0]['name']);
    }

    public function testProductWithMissingCode()
    {
        // Criar um CSV com produto sem código
        file_put_contents($this->testFile, "Nome,Codigo,Preco\nProduto1,,10.5\nProduto2,456,-5.5\n");

        $processor = new ProductProcessor($this->testFile, ',');

        $data = $processor->parseCsv();
        $result = $processor->filterAndSortProducts($data);

        // Apenas o produto com código válido deve ser processado
        $this->assertCount(1, $result);
        $this->assertEquals('Produto2', $result[0]['name']);
    }

    public function testProductWithNegativePrice()
    {
        // Criar um CSV com produto com preço negativo
        file_put_contents($this->testFile, "Nome,Codigo,Preco\nProduto1,123,-10.5\nProduto2,456,5.5\n");

        $processor = new ProductProcessor($this->testFile, ',');

        $data = $processor->parseCsv();
        $result = $processor->filterAndSortProducts($data);

        // O produto com preço negativo deve ser ignorado
        $this->assertCount(1, $result);
        $this->assertEquals('Produto2', $result[0]['name']);
    }

    public function testDifferentSeparator()
    {
        // Criar um CSV usando ponto e vírgula como separador
        file_put_contents($this->testFile, "Nome;Codigo;Preco\nProduto1;123;10.5\nProduto2;456;5.5\n");

        $processor = new ProductProcessor($this->testFile, ';');

        $data = $processor->parseCsv();
        $result = $processor->filterAndSortProducts($data);

        // Verificar que os produtos são processados corretamente com o separador diferente
        $this->assertCount(2, $result);
        $this->assertEquals('Produto1', $result[0]['name']);
        $this->assertEquals('Produto2', $result[1]['name']);
    }

    public function testEmptyFile()
    {
        // Criar um CSV vazio
        file_put_contents($this->testFile, "");

        $processor = new ProductProcessor($this->testFile, ',');

        $data = $processor->parseCsv();
        $result = $processor->filterAndSortProducts($data);

        // Nenhum produto deve ser processado
        $this->assertCount(0, $result);
    }

    public function testInvalidPrice()
    {
        // Criar um CSV com preço inválido
        file_put_contents($this->testFile, "Nome,Codigo,Preco\nProduto1,123,abc\nProduto2,456,5.5\n");

        $processor = new ProductProcessor($this->testFile, ',');

        $data = $processor->parseCsv();
        $result = $processor->filterAndSortProducts($data);

        // O produto com preço inválido deve ser ignorado
        $this->assertCount(1, $result);
        $this->assertEquals('Produto2', $result[0]['name']);
    }
}
