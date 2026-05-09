/**Esta función convierte una cadena en un slug. La situabamos dentro de la vista create.blade.php.
 * Como quiero que se pueda usar de forma global, la he movido a un archivo js dentro de assets, y lo importo en app.js. 
 * De esta forma, la función string_to_slug estará disponible en toda la aplicación, y puedo usarla en cualquier vista 
 * que necesite convertir un título en un slug.
 * Para hacerla glogal, la asignamos al objeto window, de esta forma podemos llamarla desde cualquier parte de nuestro código.
 * En lugar de function string_to_slug(str, querySelector) {....}, la definimos como window.string_to_slug = function(str, querySelector) {....}
 */
window.string_to_slug =function (str, querySelector) {
    // Eliminar espacios al inicio y final
    str = str.replace(/^\s+|\s+$/g, '');

    // Convertir todo a minúsculas
    str = str.toLowerCase();

    // Definir caracteres especiales y sus reemplazos
    var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
    var to = "aaaaeeeeiiiioooouuuunc------";

    // Reemplazar caracteres especiales por los correspondientes en 'to'
    for (var i = 0, l = from.length; i < l; i++) {
        str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
    }

    // Eliminar caracteres no alfanuméricos y reemplazar espacios por guiones
    str = str.replace(/[^a-z0-9 -]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-');

    // Asignar el slug generado al campo de entrada correspondiente
    document.querySelector(querySelector).value = str;
}
