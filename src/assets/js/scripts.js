$(document).ready(function(){

    // Helper used on the functions below to change the eye icon and label
    function changeChild(child, child_sub, target, text = '*****') {
        if (child.hasClass('fa-eye-slash')) {
            child_sub.removeClass('fa-eye-slash').addClass('fa-eye')
            target.text(target.attr('value'))
        } else {
            child_sub.removeClass('fa-eye').addClass('fa-eye-slash')
            target.text('*****')
        }
    }

    // Check if eyes are enabled or disabled to change the DisplayAll button label and icon
    function checkVisibility(){
        $visibles = [];

        var link_btn = $(".toggle_eye_all")
        var child_btn = link_btn.find("i");
        var span = link_btn.find("span");

        $(".target_eye").each(function(){

            var link = $(this);
            var child = link.find("i");
            if (child.hasClass('fa-eye-slash')) {
                $visibles.push(1)
            } else {
                $visibles.push(0)
            }
        })

        child_btn.removeClass('fa-eye-slash').removeClass('fa-eye')
        if ($visibles.every(x=>x===0)) {
            span.text('Hide All')
            child_btn.addClass('fa-eye')
        } else {
            span.text('Display All')
            child_btn.addClass('fa-eye-slash')
        }
    }

    // Actio to the Display/Hide all button
    $(".toggle_eye_all").unbind("click").bind("click", function(){
        var link = $(this);
        var child = link.find("i");
        var span = link.find("span");

        $(".target_eye").each(function(){
            var link_sub = $(this);
            var child_sub = link_sub.find("i");

            var row = link_sub.closest('tr');
            var target = row.find(".target");

            changeChild(child, child_sub, target, '*****');
        })

        if (child.hasClass('fa-eye-slash')) {
            child.removeClass('fa-eye-slash').addClass('fa-eye') 
            span.text('Hide All')
        } else {
            child.removeClass('fa-eye').addClass('fa-eye-slash')
            span.text('Display All')
        }
    });

    // Action to the eye buttons
    $(".target_eye").unbind("click").bind("click", function(){
        var link = $(this);
        var child = link.find("i");
        var row = link.closest('tr');
        var target = row.find(".target");

        changeChild(child, child, target, '*****');
        checkVisibility()
    });

    // Prepare the Send List Button
    $(".btn_send_list").unbind('click').bind('click', function(){
        var current = $(this);
        current.css('background-color', '#99000055')
        current.find('i').removeClass('fa-paper-plane').addClass('fa-spinner').addClass('fa-spin-pulse')
        current.find('span').text('Sending...');
        current.unbind('click')
        current.removeAttr('href')

        $(".action_iframe").attr('src', '/?send=1');
    });

});