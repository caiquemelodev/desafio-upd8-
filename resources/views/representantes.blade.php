@extends('layouts.app')
@section('styles')
@endsection
@section('content')
<div class="container">
    <h2><i class="bi bi-people"></i> Lista de Representantes</h2>
    <div class="d-flex mb-2 align-items-center justify-content-between flex-wrap gap-2">
        <button class="btn btn-primary" id="btnNovoRepresentante"><i class="bi bi-plus-circle"></i> Novo Representante</button>
        <div class="input-group" style="max-width: 300px;">
            <span class="input-group-text">Buscar:</span>
            <input type="text" id="buscaRepresentante" class="form-control" placeholder="Digite para filtrar...">
        </div>
    </div>
    <div id="alertaSucesso" class="alert alert-success d-none"></div>
    <table id="tabelaRepresentantes" class="display" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>CPF</th>
                <th>Telefone</th>
                <th>Cidade</th>
                <th>Estado</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<!-- Modal Representante -->
<div class="modal fade" id="modalRepresentante" tabindex="-1" aria-labelledby="modalRepresentanteLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formRepresentante">
        <div class="modal-header">
          <h5 class="modal-title" id="modalRepresentanteLabel">Novo Representante</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="representante_id">
          <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" required>
          </div>
          <div class="mb-3">
            <label for="cpf" class="form-label">CPF</label>
            <input type="text" class="form-control" id="cpf" name="cpf" maxlength="14" required>
          </div>
          <div class="mb-3">
            <label for="telefone" class="form-label">Telefone</label>
            <input type="text" class="form-control" id="telefone" name="telefone">
          </div>
          <div class="mb-3">
            <label for="cidade_id" class="form-label">Cidade</label>
            <select class="form-control" id="cidade_id" name="cidade_id" required>
              <option value="">Selecione</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Salvar</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')

    <script>
    $(document).ready(function() {

        function carregarCidadesSelect() {
            $.get('api/cidades', function(cidades) {
                let options = '<option value="">Selecione</option>';
                cidades.forEach(function(cidade) {
                    options += `<option value="${cidade.id}">${cidade.nome} - ${cidade.estado}</option>`;
                });
                $('#cidade_id').html(options);
            });
        }


        var tabela = $('#tabelaRepresentantes').DataTable({
            ajax: {
                url: 'api/representantes',
                dataSrc: 'data',
                error: function(xhr, error, thrown) {
                    let msg = 'Erro ao carregar representantes: ' + (xhr.responseText || error);
                    alert(msg);
                    console.error('Erro DataTables:', xhr, error, thrown);
                }
            },
            columns: [
                { data: 'id' },
                { data: 'nome' },
                { data: 'cpf' },
                { data: 'telefone' },
                { data: 'cidade.nome', defaultContent: '' },
                { data: 'cidade.estado', defaultContent: '' },
                {
                    data: null,
                    orderable: false,
                    render: function (data, type, row) {
                        return `
                            <button class='btn btn-success btn-sm btn-editar' data-id='${row.id}'>Editar</button>
                            <button class='btn btn-danger btn-sm btn-excluir' data-id='${row.id}'>Excluir</button>
                        `;
                    }
                }
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
            },
            responsive: true,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50],
            dom: 'rt<"d-flex justify-content-between align-items-center mt-2"lip>',
            order: [[0, 'asc']]
        });


        $('#buscaRepresentante').on('keyup', function() {
            tabela.search(this.value).draw();
        });


        $('#btnNovoRepresentante').on('click', function() {
            $('#formRepresentante')[0].reset();
            $('#representante_id').val('');
            $('#modalRepresentanteLabel').text('Novo Representante');
            carregarCidadesSelect();
            $('#modalRepresentante').modal('show');
        });


        $('#formRepresentante').on('submit', function(e) {
            e.preventDefault();
            let id = $('#representante_id').val();
            let url = id ? `api/representantes/${id}` : 'api/representantes';
            let method = id ? 'PUT' : 'POST';
            let dados = {
                nome: $('#nome').val(),
                cpf: $('#cpf').val(),
                telefone: $('#telefone').val(),
                cidade_id: $('#cidade_id').val()
            };
            if (id) dados.id = id;
            $.ajax({
                url: url,
                method: method,
                data: dados,
                success: function(resp) {
                    $('#modalRepresentante').modal('hide');
                    $('#alertaSucesso').removeClass('d-none').text('Representante salvo com sucesso!');
                    tabela.ajax.reload();
                    setTimeout(() => { $('#alertaSucesso').addClass('d-none'); }, 2000);
                },
                error: function(xhr) {
                    let msg = 'Erro ao salvar representante.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg += '\n' + xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                        msg += '\n' + xhr.responseText;
                    }
                    alert(msg);
                }
            });
        });

        // Editar representante
        $('#tabelaRepresentantes').on('click', '.btn-editar', function() {
            let id = $(this).data('id');
            $.get(`api/representantes/${id}`, function(rep) {
                $('#representante_id').val(rep.id);
                $('#nome').val(rep.nome);
                $('#cpf').val(rep.cpf);
                $('#telefone').val(rep.telefone);
                carregarCidadesSelect();
                setTimeout(() => { $('#cidade_id').val(rep.cidade_id); }, 200); // aguarda cidades carregar
                $('#modalRepresentanteLabel').text('Editar Representante');
                $('#modalRepresentante').modal('show');
            });
        });

        // Excluir representante
        $('#tabelaRepresentantes').on('click', '.btn-excluir', function() {
            if (!confirm('Tem certeza que deseja excluir este representante?')) return;
            let id = $(this).data('id');
            $.ajax({
                url: `api/representantes/${id}`,
                method: 'DELETE',
                success: function() {
                    $('#alertaSucesso').removeClass('d-none').text('Representante excluído com sucesso!');
                    tabela.ajax.reload();
                    setTimeout(() => { $('#alertaSucesso').addClass('d-none'); }, 2000);
                },
                error: function() {
                    alert('Erro ao excluir representante.');
                }
            });
        });
    });
    </script>
@endsection
