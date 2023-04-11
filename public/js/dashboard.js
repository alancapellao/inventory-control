$(function () {
    // Seleciona o formulário e o cria uma váriavel para armazenar o ID
    const form = $("#form-product");
    let productId;

    $(".jsFilter").on("click", function () {
        $(".filter-menu").toggleClass("active");
    });

    $(".grid").on("click", function () {
        $(".list").removeClass("active");
        $(".grid").addClass("active");
        $(".products-area-wrapper").addClass("gridView");
        $(".products-area-wrapper").removeClass("tableView");
        $(".products-column").addClass("gridView");
    });

    $(".list").on("click", function () {
        $(".list").addClass("active");
        $(".grid").removeClass("active");
        $(".products-area-wrapper").removeClass("gridView");
        $(".products-area-wrapper").addClass("tableView");
        $(".products-column").removeClass("gridView");
    });

    var modeSwitch = $('.mode-switch');
    modeSwitch.on('click', function () {
        $('html').toggleClass('light');
        modeSwitch.toggleClass('active');
    });

    // Adiciona um token CSRF em todas as requisições Ajax
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })

    // Exibe o formulário quando o botão "Adicionar Produto" é clicado
    $("#addProduct").on("click", function (e) {
        $(".box").css("display", "block");
        $("#delete").css("display", "none");
        $(".overlay").show();

        productId = 0;
    });

    // Esconde o formulário e limpa seus campos quando o botão "Cancelar" é clicado
    $("#cancel").on("click", function (e) {
        $(".box").css("display", "none");
        $(".overlay").hide();

        if (form.length) {
            form[0].reset();
        }
    });

    // Faz logout do usuário quando o botão "Sair" é clicado
    $("#logout").on("click", function (e) {
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: "/logout",
            success: function (e) {
                window.location.href = "/index";
            }
        });
    });

    // Exibe o nome do usuário logado na conta
    $.ajax({
        url: '/usuario',
        method: 'GET',
        success: function (data) {
            $(".account-info-name").html(data['name']);
        }
    });

    // Armazenar categoria selecionada
    const menuDropdown = $("#category");
    const menuOptions = menuDropdown.find("option");
    let selectedOption;

    menuDropdown.on("change", function () {
        selectedOption = $(this).find("option:selected");
    });

    // Obter dados dos produtos usando método GET e adicioná-los na página HTML
    $.ajax({
        url: '/products',
        method: 'GET',
        success: function (data) {
            const produtos = data.produtos;
            const productsArea = $('.products-column');

            // Criar HTML que exibirá informações do produto
            produtos.forEach((produto) => {
                const html = `
                <div class="products-row" data-product-id="${produto.id}">
                        <div class="product-cell image">
                            <span>${produto.item}</span>
                        </div>
                        <div class="product-cell category"><span class="cell-label">Category:</span>${produto.category}</div>
                        <div class="product-cell status-cell">
                        <span class="cell-label">Status:</span>
                        <span class="status ${produto.status}">${produto.status}</span>
                        </div>
                        <div class="product-cell sales"><span class="cell-label">Sales:</span>${produto.sale}</div>
                        <div class="product-cell stock"><span class="cell-label">Stock:</span>${produto.stock}</div>
                        <div class="product-cell price"><span class="cell-label">Price:</span>$ ${produto.price}</div>
                    </div>
                `;
                // Adicionar HTML criado na área de produtos na página
                productsArea.append(html);
            });
        }
    });

    // Obter informações de um produto ao clicar nele na página
    $('.products-column').on('click', '.products-row', function () {
        // Obter o ID do produto
        productId = $(this).data('product-id');

        // Fazer solicitação AJAX para obter informações do produto
        $.ajax({
            url: `/product/${productId}`,
            method: 'GET',
            success: function (data) {

                const produto = data.produto;

                // Preencher o formulário com as informações do produto
                $('#item').val(produto.item);
                $('#category').val(produto.category);
                if (produto.status === "active") {
                    $('input[name="status"][value="active"]').prop('checked', true);
                } else {
                    $('input[name="status"][value="disabled"]').prop('checked', true);
                }
                $('#sale').val(produto.sale);
                $('#stock').val(produto.stock);
                $('#price').val(produto.price);

                // Mostrar o formulário
                $(".box").css("display", "block");
                $("#delete").css("display", "block");
                $(".overlay").show();
            }
        });
    });

    // Enviar formulário para adicionar ou atualizar um produto
    $("#submit").on("click", function (e) {
        e.preventDefault();

        // Definir a URL e o método para enviar a solicitação AJAX
        let url, method;

        if (productId !== 0 && productId !== undefined && productId !== null) {
            url = `/update/${productId}`;
            method = 'PUT';
        } else {
            url = '/save';
            method = 'POST';
        }

        // Obter valor que está selecionado na categoria
        const selectedOption = $("#category option:selected");

        $.ajax({
            type: method,
            url: url,
            data: {
                item: $("#item").val(),
                category: selectedOption.text(),
                status: $('input[name="status"]:checked').val(),
                sale: $("#sale").val(),
                stock: $("#stock").val(),
                price: $("#price").val()
            },
            success: function (retorno) {
                // Esconder formulário e botão de delete
                $(".box").css("display", "none");
                $("#delete").css("display", "none");
                $(".overlay").hide();

                // Fazer solicitação AJAX para pegar a lista atualizada de produtos e renderizá-la na página
                $.ajax({
                    url: '/products',
                    method: 'GET',
                    success: function (data) {
                        const produtos = data.produtos;
                        const productsArea = $('.products-column');

                        productsArea.empty();

                        produtos.forEach((produto) => {
                            const html = `
                            <div class="products-row" data-product-id="${produto.id}">
                                <div class="product-cell image">
                                    <span>${produto.item}</span>
                                </div>
                                <div class="product-cell category"><span class="cell-label">Category:</span>${produto.category}</div>
                                <div class="product-cell status-cell">
                                <span class="cell-label">Status:</span>
                                <span class="status ${produto.status}">${produto.status}</span>
                                </div>
                                <div class="product-cell sales"><span class="cell-label">Sales:</span>${produto.sale}</div>
                                <div class="product-cell stock"><span class="cell-label">Stock:</span>${produto.stock}</div>
                                <div class="product-cell price"><span class="cell-label">Price:</span>$ ${produto.price}</div>
                            </div>
                        `;
                            productsArea.append(html);
                        });
                    }
                });
                // Limpar o formulário
                if (form.length) {
                    form[0].reset();
                }
            }
        });
    });
    // Excluir um produto
    $('#delete').on('click', function () {
        // Confirmar com o usuário antes de excluir
        const confirmDelete = confirm('Are you sure you want to delete this product?');

        if (confirmDelete) {
            // Fazer solicitação AJAX para excluir o produto
            $.ajax({
                url: `/delete/${productId}`,
                method: 'DELETE',
                success: function (data) {
                    $(".box").css("display", "none");
                    $(".overlay").hide();
                    // Fazer solicitação AJAX para pegar a lista atualizada de produtos e renderizá-la na página
                    $.ajax({
                        url: '/products',
                        method: 'GET',
                        success: function (data) {
                            const produtos = data.produtos;
                            const productsArea = $('.products-column');

                            productsArea.empty();

                            produtos.forEach((produto) => {
                                const html = `
                                <div class="products-row" data-product-id="${produto.id}">
                                    <div class="product-cell image">
                                        <span>${produto.item}</span>
                                    </div>
                                    <div class="product-cell category"><span class="cell-label">Category:</span>${produto.category}</div>
                                    <div class="product-cell status-cell">
                                    <span class="cell-label">Status:</span>
                                    <span class="status ${produto.status}">${produto.status}</span>
                                    </div>
                                    <div class="product-cell sales"><span class="cell-label">Sales:</span>${produto.sale}</div>
                                    <div class="product-cell stock"><span class="cell-label">Stock:</span>${produto.stock}</div>
                                    <div class="product-cell price"><span class="cell-label">Price:</span>$ ${produto.price}</div>
                                </div>
                            `;
                                productsArea.append(html);
                            });
                        }
                    });
                    if (form.length) {
                        form[0].reset();
                    }

                    //Reinicia váriavel de ID
                    productId = 0;
                }
            });
        }
    });
    // Adicionando um listener ao evento de digitação na barra de busca
    $('.search-bar').on('keyup', function () {
        const searchTerm = $(this).val();
        // Realizando uma requisição AJAX para a rota "/search"
        $.ajax({
            url: '/search',
            method: 'POST',
            data: { search: searchTerm },
            success: function (data) {
                // Obtendo a lista de produtos retornados pela busca
                const produtos = data.produtos;

                const productsArea = $('.products-column');

                productsArea.empty();

                produtos.forEach((produto) => {
                    const html = `
                    <div class="products-row" data-product-id="${produto.id}">
                        <div class="product-cell image">
                            <span>${produto.item}</span>
                        </div>
                        <div class="product-cell category"><span class="cell-label">Category:</span>${produto.category}</div>
                        <div class="product-cell status-cell">
                        <span class="cell-label">Status:</span>
                        <span class="status ${produto.status}">${produto.status}</span>
                        </div>
                        <div class="product-cell sales"><span class="cell-label">Sales:</span>${produto.sale}</div>
                        <div class="product-cell stock"><span class="cell-label">Stock:</span>${produto.stock}</div>
                        <div class="product-cell price"><span class="cell-label">Price:</span>$ ${produto.price}</div>
                    </div>
                `;
                    productsArea.append(html);
                });
            }
        });
    });

    //Escondendo e mostrando botão de logout
    $('.account-info-more').on('click', function () {
        $('.account-info-dropdown').toggleClass('show');
    });
});
