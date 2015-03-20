(function ($, window, document) {

    // The $ is now locally scoped 
    $(function () {

        // DOM ready!
        //Variables
        $document = $(document),
            $window = $(window),
            $body = $("body"),
            $content = $("#content"),
            $worksPagination = $("#works-pagination"),
            maxNumPages = $worksPagination.attr("max-num-pages"),
            i = 1;



        // Initial setup


        // Event delegation
        $window.on("scroll", function () {
            if ($window.scrollTop() == $document.height() - $window.height()) {
                getWorks();
            }
        });


    });


    // Functions


    function getWorks() {
        if (i < maxNumPages) {
            var linkURL = $worksPagination.find("a").attr("href");
            $.ajax({
                // the URL for the request
                url: linkURL,

                // whether this is a POST or GET request
                type: "GET",

                // function to call before we send the AJAX request
                beforeSend: startFn,

                // function to call for success
                success: successFn,

                // function to call on an error
                error: errorFn,

                // code to run regardless of success or failure
                complete: function (xhr, status) {
                    console.log("The request is complete!");
                }
            });
        }

    }

    function startFn() {
    }

    function successFn(result) {
        i++;
        //$tiles = result.find(".tile");
        $("#content .block-grid").append($(result).find(".tile"));
        $worksPagination.children().remove();
        $worksPagination.append($(result).find("#works-pagination a"));
    }

    function errorFn(xhr, status, strErr) {
        console.log("There was an error!");
    }

}(window.jQuery, window, document)); // Fully reference jQuery after this point.