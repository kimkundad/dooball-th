
function showTimeline(eventList, homeTeamName) {
    if (eventList.length > 0) {
        var li = '';
        var evRow = null;
        var content = '';

        for (var i = 0; i < eventList.length; i++) {
            evRow = eventList[i];
            content = '';

            if (evRow.type == 'Goal') {
                content = '<div class="d-flex alit-center">';
                if (evRow.detail == 'Normal Goal') {
                    content += '<img src="' + $('#image_icon').val() + '" alt="">';
                } else if (evRow.detail == 'Penalty') {
                    content += '<img src="' + $('#image_icon').val() + '" alt="">';
                    content += '<span class="text-danger ml-1">(จุดโทษ)</span>';
                }

                content += '<span class="check-detail">' + evRow.player + '</span>';
                content += '</div>';
            } else if (evRow.type == 'Card') {
                content = '<p class="d-flex alit-center just-end">';
                content += '<span class="mr-10">' + evRow.player + '</span>';

                if (evRow.detail == 'Yellow Card') {
                    content += '<span class="match-cube draw"></span>';
                } else if (evRow.detail == 'Red Card') {
                    content += '<span class="match-cube win"></span>';
                }

                content += '</p>';
            } else if (evRow.type == 'subst') {
                content = '<span class="d-flex alit-center mr-20"><i class="fa fa-long-arrow-alt-down text-danger"></i><span class="check-detail">' + evRow.detail + '</span></span>';
                content += '<span class="d-flex"><i class="fa fa-long-arrow-alt-up text-success"></i><span class="check-detail">' + evRow.player + '</span></span>';
            }

            var liClass = (evRow.teamName == homeTeamName) ? 'home' : 'away';

            li += '<li class="' + liClass + '">';
            li +=   "<div class=\"point d-flex alit-center just-center\">" + evRow.elapsed + "'</div>";
            li +=   '<div class="date d-flex">' + content + '</div>';
            li += '</li>';
        }

        $('.tm-list').html(li);
    }
}