<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
  // Abrir modal y cargar formulario de agregar usuario
  $("#agregarusuario").click(function () {
    $("#formularioContainer").load("agregar.php", function () {
      $("#formularioContainer").removeClass("hidden");
    });
  });

  // Enviar formulario por AJAX
  $(document).on("submit", "#formulario", function (e) {
    e.preventDefault();

    $.ajax({
      type: "POST",
      url: "proceso.php", // Asegúrate de que este archivo procese el formulario
      data: $(this).serialize(),
      success: function (respuesta) {
        $("#respuesta").html(respuesta); // Asegúrate de tener un div con id="respuesta"
        $("#formulario")[0].reset();     // Limpia el formulario después de enviar
      },
      error: function () {
        $("#respuesta").html("Error al enviar el formulario.");
      }
    });
  });

  // Cerrar el modal
  $(document).on("click", "#cerrarFormulario", function () {
    $("#formularioContainer").addClass("hidden").empty();
  });
</script>
