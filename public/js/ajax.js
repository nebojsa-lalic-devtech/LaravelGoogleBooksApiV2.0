// $(document).ready(function(){
//     $("#bookSearchForm").submit(function(event) {
//         event.preventDefault();
//         var isbnInput = document.forms["bookSearchForm"]["isbn"].value;
//         if (isbnInput.length != 13 || isNaN(isbnInput))            {
//             alert("Your ISBN must be combination of 13 numbers. Try again");
//             return false;
//         } else {
//             // GOOGLE BOOK API WITH GUZZLE
//             $.ajax({
//                 url: '/searchBookGoogle',
//                 type: "GET",
//                 async: false,
//                 data: {
//                     'isbn': $('#isbn').val()
//                 },
//                 dataType: 'json',
//                 success: function (data) {
//                     $("#google_thumbnail").attr("src",data.thumbnail);
//                     $("#google_isbn").html(data.isbn);
//                     $("#google_title").html(data.title);
//                     $("#google_author").html(data.author);
//                     // DATABASE SEARCH FOR FOR BOOK
//                     $.ajax({
//                         url: '/searchBookDevtech',
//                         type: "GET",
//                         data: {
//                             'isbn': $('#isbn').val()
//                         },
//                         dataType: 'json',
//                         success: function (data) {
//                             $("#devtech_book_status").html('available');
//                             $("#info_library").html('Good news, we have book: "' + data.title + '" in DevTech library! :)');
//                         },
//                         error: function (xhr, status, error) {
//                             $("#devtech_book_status").html('not available');
//                             $("#info_library").html(xhr.responseText);
//                         }
//                     });
//                 },
//                 error: function (xhr, status, error) {
//                     $('#table_book_info').remove();
//                     $("#info_library").html(xhr.responseText);
//                 }
//             });
//         }
//     });
// });