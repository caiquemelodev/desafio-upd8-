-- Script: Retorna todos os representantes que podem atender um cliente (por ID do cliente)
SELECT r.*
FROM representantes r
JOIN cidades c ON r.cidade_id = c.id
JOIN clientes cl ON cl.cidade_id = c.id
WHERE cl.id = :cliente_id;
