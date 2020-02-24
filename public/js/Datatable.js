$('.Produccion').DataTable( {
    scrollY:        '50vh',
    scrollCollapse: true,
    "pageLength": 50,
    "lengthMenu": [[50, 100, -1], [50, 100, "Todos"]],
    "language": {
        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
    },
    "ordering": false
} );