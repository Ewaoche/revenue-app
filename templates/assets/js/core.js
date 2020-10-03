$(document).ready(function() {

    $("a[href='#']").click(function(e) {
        e.preventDefault();
    });
    $("a[href='']").click(function(e) {
        e.preventDefault();
    });

    $(".xTaxDataSense").keyup(function(e) {
        e.preventDefault();
    });

    $("#xDemandNoticeButton").attr("disabled", true);
    var itemsCount = 0;
    $("#xDemandNoticeButton").click(function(e) {
        if (itemsCount <= 0) {
            e.preventDefault();
        }
    });

    var itemsCount = 0;
    var itemsTotalPrice = 0;
    var intToAdded = 0;
    var intToRemove = 0;

    $(".xLivePackage").change(function(e) {
        var CloseRow = $(this).closest(".volatile");
        var val_in = this.value;
        if (this.checked) {
            var html = '<li class="list-group-item xLivePackageListItem d-flex justify-content-between align-items-center" id="package_' + val_in + '">' + $(this).data("name") + '<span class="badge badge-primary badge-pill">' + $(this).data("pricetag") + '</span></li>';
            $("#xLivePackageList").append(html);
            itemsCount++;
            intToAdded = parseFloat($(this).data("price"));
            itemsTotalPrice = parseFloat(itemsTotalPrice + intToAdded);
            CloseRow.addClass("bg-gray");
        } else {
            $curre_package = $('#package_' + val_in);
            $curre_package.remove();
            itemsCount--;
            intToRemove = parseFloat($(this).data("price"));
            itemsTotalPrice = parseFloat(itemsTotalPrice - intToRemove);
            CloseRow.removeClass("bg-gray");
        }
        if (itemsCount <= 0) {
            $("#xDemandNoticeButton").attr("disabled", true);
        } else if (itemsCount >= 1) {
            $("#xDemandNoticeButton").attr("disabled", false);
        }
        $("#xLiveItemCount").html(itemsCount);
        $("#xLiveItemTotalCost").html(itemsTotalPrice).toFixed(1);
    });


    $(".xLiveSearch").keyup(function(e) {
        var val_in = this.value;
        var key_in = e.which;
        if (key_in == 13) {
            e.preventDefault();
        }

        // Declare variables
        var input, filter, ul, li, a, i, txtValue;
        input = document.getElementById('xLiveSearch');
        filter = input.value.toUpperCase();
        ul = document.getElementById("myUL");
        li = ul.getElementsByTagName('li');

        // Loop through all list items, and hide those who don't match the search query
        for (i = 0; i < li.length; i++) {
            a = li[i].getElementsByTagName("a")[0];
            txtValue = a.textContent || a.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }
        }
    });




    $('.xDataTables').DataTable();



});