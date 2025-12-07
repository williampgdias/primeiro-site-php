<?php

declare(strict_types=1);

class Guestbook
{
    private string $arquivo;

    public function __construct(string $caminhoArquivo)
    {
        $this->arquivo = $caminhoArquivo;
    }

    public function salvar(string $nome, string $texto): void
    {
        $lista = $this->ler();

        $novaMensagem = [
            'nome' => $nome,
            'texto' => $texto,
            'data' => date('d/m/Y H:i')
        ];

        array_unshift($lista, $novaMensagem);

        file_put_contents($this->arquivo, json_encode($lista, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public function ler(): array
    {
        if (!file_exists($this->arquivo)) {
            return [];
        }
        $json = file_get_contents($this->arquivo);
        $lista = json_decode($json, true);
        return is_array($lista) ? $lista : [];
    }
}