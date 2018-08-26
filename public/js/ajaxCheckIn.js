$(document).on("submit", '.checkIn', function(e) {
    td = $(this).parent();
    nextTd = td.next();
    studentId = $(this).find('input[name=id]').val();
    token = $(this).find('input[name=_token]').val();

    checkOutHtml = '<form class="checkOut" method="post" action="/student/checkOut"><input type="hidden" name="_token" value="'+token+'"><input type="hidden" name="id" value="'+studentId+'"><button type="submit" class="btn btn-warning btn-sm">Check-Out</button></form>'

    $.ajax({
        type: "POST",
        url: url + "students/checkIn",
        data: $(this).serialize(), // serializes the form's elements.
        success: function (data) {
            td.html(data);
            nextTd.html(checkOutHtml);
        }
    });

    e.preventDefault(); // avoid to execute the actual submit of the form.
});

$(document).on("submit", '.checkOut', function(e) {
    td = $(this).parent();

    $.ajax({
        type: "POST",
        url:  url + "students/checkOut",
        data: $(this).serialize(), // serializes the form's elements.
        success: function (data) {
            td.html(data);
        }
    });

    e.preventDefault(); // avoid to execute the actual submit of the form.
});

$(".action").click(function(e) {
    // str = table.$('input:checkbox[name="students[]"]:checked').serialize();
    // window.location = '' + $(this).attr('id') + '?' + str;
    str = '?';
    rows = table.rows( { selected: true } ).data();
    console.log(rows);
    $.each(rows, function(index, value) {
        str = str + 'students[]=' + value.id + '&';
    });
    console.log(str);
    window.location = url + "students/" + $(this).attr('id') + str;
});
