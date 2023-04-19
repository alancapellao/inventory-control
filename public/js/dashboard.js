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

    // Função para obter dados dos produtos usando método GET e adicioná-los na página HTML
    function getProducts(produto) {

        let url, method;

        if (produto !== 0 && produto !== undefined && produto !== null) {
            url = `/search`;
            method = 'POST';
        } else {
            url = '/products';
            method = 'GET';
        }

        $.ajax({
            url: url,
            method: method,
            data: { search: produto },
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
    }

    // Renderizando produtos ao carregar página
    getProducts(0);

    // Obter informações de um produto ao clicar nele na página
    $('.products-column').on('click', '.products-row', function () {

        productId = $(this).data('product-id');

        $.ajax({
            url: `/product/${productId}`,
            method: 'GET',
            success: function (data) {

                const produto = data.produto;

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

        let url, method;

        if (productId !== 0 && productId !== undefined && productId !== null) {
            url = `/update/${productId}`;
            method = 'PUT';
        } else {
            url = '/save';
            method = 'POST';
        }

        const item = $("#item").val();
        const category = $("#category option:selected").val();
        const status = $('input[name="status"]:checked').val();
        const sale = $("#sale").val();
        const stock = $("#stock").val();
        const price = $("#price").val();

        if (item.trim() == "" || category == "" || status == undefined || sale.trim() == "" || stock.trim() == "" || price.trim() == "") {
            alert("Fill in the fields.");
        } else {
            $.ajax({
                type: method,
                url: url,
                data: {
                    item,
                    category,
                    status,
                    sale,
                    stock,
                    price
                },
                success: function (data) {

                    if (data['error']) {
                        alert(data['message']);
                    } else {
                        alert(data['message']);

                        $(".box").css("display", "none");
                        $("#delete").css("display", "none");
                        $(".overlay").hide();

                        getProducts(0);

                        if (form.length) {
                            form[0].reset();
                        }
                    }
                }
            });
        }
    });

    // Excluindo um produto
    $('#delete').on('click', function () {

        const confirmDelete = confirm('Are you sure you want to delete this product?');

        if (confirmDelete) {
            $.ajax({
                url: `/delete/${productId}`,
                method: 'DELETE',
                success: function (data) {

                    if (data['error']) {
                        alert(data['message']);
                    } else {
                        alert(data['message']);

                        $(".box").css("display", "none");
                        $(".overlay").hide();

                        getProducts(0);

                        if (form.length) {
                            form[0].reset();
                        }
                    }
                }
            });
        }
    });

    // Adicionando um listener ao evento de digitação na barra de busca
    $('.search-bar').on('keyup', function () {
        const searchTerm = $(this).val();

        getProducts(searchTerm);
    });

    //Escondendo e mostrando botão de logout
    $('.account-info-more').on('click', function () {
        $('.account-info-dropdown').toggleClass('show');
    });

    var currentUrl = window.location.pathname;

    // Adiciona um manipulador de eventos ao clicar em alguma das páginas
    $(".sidebar-list-item a").filter(function () {
        return $(this).attr("href") === currentUrl;
    }).closest(".sidebar-list-item").addClass("active");

    $(".sidebar-list-item a").click(function () {
        $(".sidebar-list-item.active").removeClass("active");
        $(this).closest(".sidebar-list-item").addClass("active");
    });
});
