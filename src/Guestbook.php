<?php

declare(strict_types=1);

// Import Carbon
use Carbon\Carbon;

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
            'id' => uniqid(),
            'nome' => $nome,
            'texto' => $texto,
            'data_hora' => Carbon::now()->toIso8601String()
        ];

        array_unshift($lista, $novaMensagem);
        $this->gravarNoDisco($lista);
    }

    public function excluir(string $id): void
    {
        $lista = $this->ler();

        $novaLista = array_filter($lista, function ($msg) use ($id) {
            return ($msg['id'] ?? '') !== $id;
        });

        $this->gravarNoDisco(array_values($novaLista));
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

    private function gravarNoDisco(array $dados): void
    {
        file_put_contents($this->arquivo, json_encode($dados, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}