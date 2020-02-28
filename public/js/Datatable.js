$('.Produccion').DataTable( {
    "scrollY": '55vh',
    "scrollX": true,
    scrollCollapse: true,
    "pageLength": 50,
    "lengthMenu": [[50, 100, -1], [50, 100, "Todos"]],
    "language": {
        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
    },
    "ordering": false
} );