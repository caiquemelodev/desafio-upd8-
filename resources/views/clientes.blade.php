@extends('layouts.app')
@section('styles')
    <style>
        .d-flex.mb-3 { gap: 10px; }
        #btnPdf { min-width: 70px; }
        .modal-header {
            background: #f8fafc;
            border-bottom: 1px solid #e5e5e5;
        }
        .modal-title {
            font-weight: 600;
            font-size: 1.3rem;
        }
        .modal-content {
            border-radius: 12px;
            box-shadow: 0 4px 24px #0002;
        }
        .modal-body label.form-label {
            font-weight: 500;
        }
        .modal-body .form-control, .modal-body .form-select {
            border-radius: 18px;
        }
        .modal-footer {
            border-top: none;
        }
        @media (max-width: 768px) {
            .modal-dialog { max-width: 98vw; margin: 1.75rem auto; }
        }
    </style>
@endsection
@section('content')
<div class="container" style="max-width:1100px;">
    <h2>Clientes</h2>
    <div id="alertSucesso" class="alert alert-success d-none" role="alert" style="position:relative;z-index:1056;">
        <span id="alertSucessoMsg">Cliente salvo com sucesso!</span>
    </div>
    <div class="d-flex mb-2 align-items-center justify-content-between flex-wrap gap-2">
        <button type="button" class="btn btn-primary" id="btnNovoCliente" data-bs-toggle="modal" data-bs-target="#modalCliente">
            <i class="bi bi-plus"></i> Novo Cliente
        </button>
        <div class="input-group" style="max-width: 300px;">
            <span class="input-group-text">Buscar:</span>
            <input type="text" id="buscaCliente" class="form-control" placeholder="Digite para filtrar...">
        </div>
    </div>
    <!-- Modal Cadastro Cliente -->
    <div class="modal fade" id="modalCliente" tabindex="-1" aria-labelledby="modalClienteLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalClienteLabel"><i class="bi bi-person-plus"></i> Cadastro Cliente</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="formCliente">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label">CPF</label>
                        <input type="text" class="form-control" name="cpf">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Nome</label>
                        <input type="text" class="form-control" name="nome">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Data Nascimento</label>
                        <input type="date" class="form-control" name="data_nascimento">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Sexo</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="sexo" value="M" checked>
                            <label class="form-check-label">Masculino</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="sexo" value="F">
                            <label class="form-check-label">Feminino</label>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Endereço</label>
                        <input type="text" class="form-control" name="endereco">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Estado</label>
                        <select class="form-select" name="estado" id="estadoSelect"></select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Cidade</label>
                        <select class="form-select" name="cidade_id" id="cidadeSelect"></select>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary me-2"><i class="bi bi-check2-square"></i> Salvar</button>
                    <button type="reset" class="btn btn-secondary">Limpar</button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="table-responsive">
        <table id="tabelaClientes" class="display table table-striped align-middle" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>CPF</th>
                <th>Data Nasc.</th>
                <th>Sexo</th>
                <th>Endereço</th>
                <th>Cidade</th>
                <th>Estado</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')

    <script>
    let clientesTable;
    let editClienteId = null;
    $(document).ready(function() {
        // Carregar estados e cidades
        let cidadesData = [];
        $.get('api/cidades', function(data) {
            cidadesData = data;
            let estados = [...new Set(data.map(c => c.estado))];
            $('#estadoSelect').html('<option value="">Selecione</option>' + estados.map(e => `<option value="${e}">${e}</option>`).join(''));
            $('#cidadeSelect').html('<option value="">Selecione</option>');
            $('#estadoSelect').on('change', function() {
                let estado = $(this).val();
                let cidades = data.filter(c => c.estado === estado);
                $('#cidadeSelect').html('<option value="">Selecione</option>' + cidades.map(c => `<option value="${c.id}">${c.nome}</option>`).join(''));
            });
        });


        clientesTable = $('#tabelaClientes').DataTable({
            ajax: {
                url: 'api/clientes',
                dataSrc: function(json) {
                    if (json.data) {
                        return json.data;
                    } else {
                        return [];
                    }
                }
            },
            columns: [
                { data: 'id' },
                { data: 'nome' },
                { data: 'cpf' },
                { data: 'data_nascimento' },
                { data: 'sexo' },
                { data: 'endereco' },
                { data: 'cidade.nome' },
                { data: 'cidade.estado' },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `<div class='d-flex gap-2'>
                            <button class='btn btn-success btn-sm btn-editar' data-id='${row.id}' title='Editar'><i class='bi bi-pencil-square'></i> Editar</button>
                            <button class='btn btn-danger btn-sm' data-id='${row.id}' title='Excluir'><i class='bi bi-trash'></i> Excluir</button>
                        </div>`;
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


        $('#buscaCliente').on('keyup', function() {
            clientesTable.search(this.value).draw();
        });

        // Ao clicar em Editar
        $('#tabelaClientes tbody').on('click', '.btn-editar', function() {
            const id = $(this).data('id');
            $.get('api/clientes/' + id, function(cliente) {
                editClienteId = id;
                // Preencher campos do modal
                $("#modalClienteLabel").html("<i class='bi bi-pencil-square'></i> Editar Cliente");
                $("#formCliente [name='cpf']").val(cliente.cpf).prop('readonly', false);
                $("#formCliente [name='nome']").val(cliente.nome);
                $("#formCliente [name='data_nascimento']").val(cliente.data_nascimento);
                $("#formCliente [name='sexo'][value='"+cliente.sexo+"']").prop('checked', true);
                $("#formCliente [name='endereco']").val(cliente.endereco);
                // Estado e cidade
                $("#estadoSelect").val(cliente.cidade.estado).trigger('change');
                setTimeout(function() {
                    $("#cidadeSelect").val(cliente.cidade_id);
                }, 200);
                // Abrir modal
                $('#modalCliente').modal('show');
            });
        });

        // Ao clicar em Excluir
        $('#tabelaClientes tbody').on('click', '.btn-danger', function() {
            const id = $(this).closest('button').data('id');
            if (confirm('Tem certeza que deseja excluir este cliente?')) {
                $.ajax({
                    url: 'api/clientes/' + id,
                    method: 'DELETE',
                    success: function() {
                        clientesTable.ajax.reload(null, false);
                        // Exibir alerta temporário de exclusão
                        $('#alertSucessoMsg').text('Cliente excluído com sucesso!');
                        $('#alertSucesso').removeClass('d-none').addClass('show');
                        setTimeout(function() {
                            $('#alertSucesso').removeClass('show').addClass('d-none');
                            $('#alertSucessoMsg').text('Cliente salvo com sucesso!');
                        }, 2000);
                    },
                    error: function() {
                        alert('Erro ao excluir cliente!');
                    }
                });
            }
        });


        $('#btnNovoCliente').on('click', function() {
            editClienteId = null;
            $("#modalClienteLabel").html("<i class='bi bi-person-plus'></i> Cadastro Cliente");
            $('#formCliente')[0].reset();
            $('#estadoSelect').val('');
            $('#cidadeSelect').html('<option value="">Selecione</option>');
        });


        $('#formCliente').on('submit', function(e) {
            e.preventDefault();
            let formData = $(this).serialize();
            let method, url;
            if (editClienteId) {
                method = 'PUT';
                url = 'api/clientes/' + editClienteId;
            } else {
                method = 'POST';
                url = 'api/clientes';
            }
            $.ajax({
                url: url,
                method: method,
                data: formData,
                success: function() {
                    $('#modalCliente').modal('hide');
                    $('#formCliente')[0].reset();
                    editClienteId = null;
                    clientesTable.ajax.reload(null, false);
                    // Exibir alerta temporário
                    $('#alertSucesso').removeClass('d-none').addClass('show');
                    setTimeout(function() {
                        $('#alertSucesso').removeClass('show').addClass('d-none');
                    }, 2000);
                },
                error: function(xhr) {
                    let msg = 'Erro ao salvar cliente!';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg += '\n' + xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                        msg += '\n' + xhr.responseText;
                    }
                    alert(msg);
                }
            });
        });
    });
    </script>
@endsection
