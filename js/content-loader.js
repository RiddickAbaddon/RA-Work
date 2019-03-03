var loaded_projects = 0;
var can_send = true;
var can_load = true;
var end_of_projects = false;
var selected = {
    type: null,
    id: null
}
var last_selected = {
    type: null,
    id: null
}
var filter_data = {
    "filter": 0,
    "sort": 0,
    "direction": 1,
    "phrase": ''
};

var ScrollbarList = window.Scrollbar;
ScrollbarList.use(window.OverscrollPlugin);

ScrollbarList.init($('.list')[0], {
    damping: 0.12,
    thumbMinSize: 20,
    renderByPixels: true,
    alwaysShowTracks: false,
    continuousScrolling: true,
    plugins: {
        overscroll: {
            effect: 'bounce',
            damping: 0.2,
            maxOverscroll: 150
        }
    }
});
ScrollbarList.init($('.list2')[0], {
    damping: 0.12,
    thumbMinSize: 20,
    renderByPixels: true,
    alwaysShowTracks: false,
    continuousScrolling: true,
    plugins: {
        overscroll: {
            effect: 'bounce',
            damping: 0.2,
            maxOverscroll: 150
        }
    }
});
ScrollbarList.init($('.scrollbar')[0], {
    damping: 0.12,
    thumbMinSize: 20,
    renderByPixels: true,
    alwaysShowTracks: false,
    continuousScrolling: true,
    plugins: {
        overscroll: {
            effect: 'bounce',
            damping: 0.2,
            maxOverscroll: 150
        }
    }
});
ScrollbarList.init($('.scrollbar')[1], {
    damping: 0.12,
    thumbMinSize: 20,
    renderByPixels: true,
    alwaysShowTracks: false,
    continuousScrolling: true,
    plugins: {
        overscroll: {
            effect: 'bounce',
            damping: 0.2,
            maxOverscroll: 150
        }
    }
});

function show_menu(menu, object = null) {
    if(menu == 1 && !$(object).hasClass('active')) {
        $(object).addClass('active');
        $('.list-panel').addClass('show');
        $('.options-panel').removeClass('show');
        $('.dots-icon').removeClass('active');
        $('.cancel-area').addClass('show');
    }
    else if(menu == 2 && !$(object).hasClass('active')) {
        $(object).addClass('active');
        $('.options-panel').addClass('show');
        $('.list-icon').removeClass('active');
        $('.list-panel').removeClass('show');
        $('.cancel-area').addClass('show');
    }
    else {
        hide_menu();
    }
}
function hide_menu() {
    $('.options-panel').removeClass('show');
    $('.dots-icon').removeClass('active');
    $('.list-icon').removeClass('active');
    $('.list-panel').removeClass('show');
    $('.cancel-area').removeClass('show');
}
function mobile_menu_hide_listener() {
    $('body').on('click', '.list-panel .project-item', hide_menu);
    $('body').on('click', '.options-panel .option', hide_menu);
}

function listener(status) {
    if(status.offset.y == status.limit.y && end_of_projects !== true) {
        load_projects();
    }
}
  
Scrollbar.get(document.querySelector('.list')).addListener(listener);

window.onload = function() {
    list.append(templates.loading());
    set_list_titles();
    load_projects();
    mobile_menu_hide_listener();
    load_content('page', 'help');
}
var list = $('.list .scroll-content');

function load_projects() {
    if(can_send) {
        can_send = false;
        $('.loading').addClass('active');
        $('.no-projects button').attr('disabled', '');
        $('.no-projects button').html('Wczytywanie...');
        $('.search-interract').addClass('disabled');

        $.ajax({
            url:        'api/getprojects.php',
            method:     'GET',
            dataType:   "json", 
            data:       {
                loaded_projects: loaded_projects,
                projects_per_query: projects_per_query,
                filter: filter_data["filter"],
                sort: filter_data["sort"],
                direction: filter_data["direction"],
                phrase: filter_data["phrase"]
            }
        })
        .done(function(response) {
            // console.log(response.query);
            $('.no-projects').remove();
            $('.loading').remove();
            if(response.data.length === 0) {
                list.append(templates.no_projects());
                end_of_projects = true;
            } else {
                response.data.forEach(function(element) {
                    list.append(templates.project_item(element));
                });
                if(response.end) {
                    list.append(templates.no_projects());
                    end_of_projects = true;
                } else {
                    list.append(templates.loading());
                    end_of_projects = false;
                }
                loaded_projects += response.data.length;
            }
            update_project_options();
            can_send = true;
            $('.search-interract').removeClass('disabled');
        })
        .fail(function( jqXHR, textStatus ) {
            console.error("Połączenie nie powiodło się: " + textStatus);
            can_send = true;
            $('.no-projects button').removeAttr('disabled', '');
            $('.no-projects button').html('Odśwież');
            $('.search-interract').removeClass('disabled');
        });
    }
}

function load_content(type, data) {
    var content = $('#content');
    var cur_iframe = $('#content iframe');
    var loading_text = $('.loading-text');
    loading_text.addClass('show');


    switch(type) {
        case "show-project": {
            if(can_load) {
                var can_show_project = true;
                if(selected.type === "project") {
                    if(selected.id === data) {
                        can_show_project = false;
                    }
                }

                if(can_show_project) {
                    can_load = false;
                    cur_iframe.addClass('hide');
                    selected = {
                        type: "project",
                        id: data
                    }
                    update_project_options();

                    $('.active[data-choose-element]').removeClass('active');
                    $('div[data-project-id="'+data+'"]').addClass('active');
                    content.append(templates.project(data));
                    
                    $('#content iframe.show')[0].contentWindow.onload = function() {loading_text.removeClass('show')};

                    setTimeout(function() {
                        cur_iframe.remove();
                        setTimeout(function() {
                            $('#content iframe.show').removeClass('show');
                            can_load = true;
                        }, 50);
                    }, 550);
                }
            }
            break;
        }
        case 'edit-project': {
            if(can_load) {
                var can_show_edit_project = true;
                if(selected.type === "edit-project") {
                    can_show_edit_project = false;
                }

                if(can_show_edit_project) {
                    can_load = false;
                    cur_iframe.addClass('hide');
                    last_selected = {
                        type: selected.type,
                        id: selected.id
                    };
                    selected.type = "edit-project";
                    update_project_options();

                    $('.active[data-choose-element]').removeClass('active');
                    $('.option[data-choose-element="edit-project"]').addClass('active');
                    content.append(templates.edit_project(selected.id));      
    
                    $('#content iframe.show')[0].contentWindow.onload = function() {loading_text.removeClass('show')};
                    
                    setTimeout(function() {
                        cur_iframe.remove();
                        setTimeout(function() {
                            $('#content iframe.show').removeClass('show');
                            can_load = true;
                        }, 50);
                    }, 550);
                }
            }
            break;
        }
        case 'add-project': {
            if(can_load) {
                var can_show_add_project = true;
                if(selected.type === "add-project") {
                    can_show_add_project = false;
                }

                if(can_show_add_project) {
                    can_load = false;
                    cur_iframe.addClass('hide');
                    last_selected = {
                        type: selected.type,
                        id: selected.id
                    };
                    selected.type = "add-project";
                    update_project_options();

                    $('.active[data-choose-element]').removeClass('active');
                    $('.option[data-choose-element="add-project"]').addClass('active');
                    content.append(templates.add_project());      
    
                    $('#content iframe.show')[0].contentWindow.onload = function() {loading_text.removeClass('show')};
                    
                    setTimeout(function() {
                        cur_iframe.remove();
                        setTimeout(function() {
                            $('#content iframe.show').removeClass('show');
                            can_load = true;
                        }, 50);
                    }, 550);
                }
            }
            break;
        }
        case 'profile': {
            if(can_load) {
                var can_show_profile = true;
                if(selected.type === "profile") {
                    if(selected.id === data) {
                        can_show_profile = false;
                    }
                }

                if(can_show_profile) {
                    can_load = false;
                    selected = {
                        type: "profile",
                        id: data
                    }
                    update_project_options();

                    $('.active[data-choose-element]').removeClass('active');
                    cur_iframe.addClass('hide');
                    $('button[data-choose-element="profile"]').addClass('active');
                    content.append(templates.profile(data));
                    
                    $('#content iframe.show')[0].contentWindow.onload = function() {loading_text.removeClass('show')};
                    
                    setTimeout(function() {
                        cur_iframe.remove();
                        setTimeout(function() {
                            $('#content iframe.show').removeClass('show');
                            can_load = true;
                        }, 50);
                    }, 550);
                }
            }
            break;
        }
        case 'page': {
            if(can_load) {
                var can_show_page = true;
                if(selected.type === "page") {
                    if(selected.id === data) {
                        can_show_page = false;
                    }
                }

                if(can_show_page) {
                    can_load = false;
                    selected = {
                        type: "page",
                        id: data
                    }
                    update_project_options();

                    $('.active[data-choose-element]').removeClass('active');
                    cur_iframe.addClass('hide');
                    $('button[data-choose-element="'+data+'"]').addClass('active');
                    content.append(templates.page(data));
                    
                    $('#content iframe.show')[0].contentWindow.onload = function() {loading_text.removeClass('show')};
    
                    setTimeout(function() {
                        cur_iframe.remove();
                        setTimeout(function() {
                            $('#content iframe.show').removeClass('show');
                            can_load = true;
                        }, 50);
                    }, 550);
                }
            }
            break;
        }
        default: {
            if(can_load) {
                can_load = false;
                selected = {
                    type: null,
                    id: null
                }
                update_project_options();

                $('.active[data-choose-element]').removeClass('active');
                cur_iframe.addClass('hide');; 

                loading_text.removeClass('show');
                    
                setTimeout(function() {
                    cur_iframe.remove();
                    can_load = true;
                }, 550);
            }
            break;
        }
    }
}

function update_project_options() {
    $('.option.project[data-choose-element]').removeClass('show');
    if(selected.type == "project") {
        if($('.project-item[data-project-id="'+selected.id+'"]').attr('data-project-end') == "false") {
            $('.option.project[data-choose-element="end-project"]').addClass('show');
            $('.option.project[data-choose-element="edit-project"]').addClass('show');
        }
        $('.option.project[data-choose-element="add-project"]').addClass('show');
        $('.option.project[data-choose-element="delete-project"]').addClass('show');
        $('.option.project[data-choose-element="copy-link-project"]').addClass('show');
    }
    else if(selected.type == "add-project") {
        $('.option.project[data-choose-element="save-project"]').addClass('show');
        $('.option.project[data-choose-element="add-project"]').addClass('show');
        $('.option.project[data-choose-element="cancel-project"]').addClass('show');
    }
    else if(selected.type == "edit-project") {
        $('.option.project[data-choose-element="save-project"]').addClass('show');
        $('.option.project[data-choose-element="edit-project"]').addClass('show');
        $('.option.project[data-choose-element="cancel-project"]').addClass('show');
    } else {
        $('.option.project[data-choose-element="add-project"]').addClass('show');
    }
}

function show_profile() {
    load_content('profile', user_id);
}
function show_page(object) {
    var page = $(object).attr('data-choose-element');
    load_content('page', page);
}
function edit_project() {
    load_content('edit-project', null);
}
function add_project() {
    load_content('add-project', null);
}
function copy_link_project() {
    var href = document.getElementById('project-iframe').contentWindow.location.href;
    if(copyTextToClipboard(href)) {
        popup({
            message: "Link zoztał skopiowany do schowka.",
            ok_button: "Ok",
            cancel_button: null
        });
    } else {
        popup({
            message: "Link do skopiowania: " + href,
            ok_button: "Ok",
            cancel_button: null
        });
    }
}
function end_project() {
    if(can_send && selected.type == 'project') {
        popup({
            message: "Czy na pewno chcesz zakończyć ten projekt? Nie będzie można później wznowić tego projektu.",
            cancel_button: "Nie",
            ok_button: "Tak",
            cancel_action: function() {},
            ok_action: function() {
                can_send = false;
                $.ajax({
                    url:        'api/end_project.php',
                    method:     'POST',
                    dataType:   "json", 
                    data:       {
                        id: selected.id
                    }
                })
                .done(function(response) {
                    if(response.success) {
                        can_send = true;
                        reset_content();
                        reset_iframe();

                    } else {
                        console.error("Nie udało się zakończyć projektu");
                    }
                })
                .fail(function( jqXHR, textStatus ) {
                    console.error("Połączenie nie powiodło się: " + textStatus);
                    can_send = true;
                });
            }
        });
        
    }
}
function delete_project() {
    if(can_send && selected.type == 'project') {
        popup({
            message: "Czy na pewno chcesz usunąć ten projekt? Wszystkie informacje oraz pliki zostaną usunięte.",
            cancel_button: "Nie",
            ok_button: "Tak",
            cancel_action: function() {},
            ok_action: function() {
                can_send = false;
                $.ajax({
                    url:        'api/delete_project.php',
                    method:     'POST',
                    dataType:   "json", 
                    data:       {
                        id: selected.id
                    }
                })
                .done(function(response) {
                    if(response.success) {
                        can_send = true;
                        reset_content();
                        cancel_iframe();
                    } else {
                        console.error("Nie udało się usunąć projektu");
                    }
                })
                .fail(function( jqXHR, textStatus ) {
                    console.error("Połączenie nie powiodło się: " + textStatus);
                    can_send = true;
                });
            }
        });
    }
}
function save_project() {
    $('#save-project-iframe')[0].contentWindow.send_project();
}
function cancel_project() {
    if(last_selected.type == 'project') {
        load_content('show-project', last_selected.id);
    } else {
        load_content(null, null);
    }
}
function cancel_iframe() {
    load_content(null, null);
}
function reset_iframe() {
    var src = $('iframe').attr('src');
    $('iframe').attr('src', src);
}
function reset_content() {
    list.html(templates.loading());
    loaded_projects = 0;
    load_projects();
}

function show_list(object) {
    var name = $(object).attr('data-target-name');
    if($(object).hasClass('active')) {
        hide_list();
    } else {
        $('.search-option.active').removeClass('active');
        $(object).addClass('active');
        $('.search-option-list[data-list-name].active').removeClass('active');
        $('.search-option-list[data-list-name="'+name+'"]').addClass('active');
    }
}
function hide_list() {
    $('.search-option.active').removeClass('active');
    $('.search-option-list[data-list-name].active').removeClass('active');
}

function set_list_titles() {
    $('.search-option[data-target-name]').each(function(i) {
        var name = $(this).attr('data-target-name');
        var input_title = $('.search-option-list[data-list-name="'+name+'"] input:checked ~ .title').text();
        var input_val = $('.search-option-list[data-list-name="'+name+'"] input:checked').val();
        $('.search-option[data-target-name="'+name+'"] span').text(input_title);
        filter_data[name] = input_val;
    });
    reset_content();
    hide_list();
}

function change_search_direction(object) {
    if($(object).hasClass('desc')) {
        $(object).removeClass('desc');
        filter_data["direction"] = 0;
    } else {
        $(object).addClass('desc');
        filter_data["direction"] = 1;
    }
    reset_content();
    load_projects();
}

$('.search-option-list label').mouseup(function() {
    setTimeout(function() {
        set_list_titles();
    }, 100);
});

$('#search').focusout(function() {
    filter_data["phrase"] = $('#search').val();
});
var search_timeout = null;
$('#search').on('input', function() {
    if(can_send) {
        search();
    } else {
        var search_interval = setInterval(function() {
            if(can_send) {
                clearInterval(search_interval);
                search();
            }
        }, 1000);
    }
});
function search() {
    clearTimeout(search_timeout);
    search_timeout = setTimeout(function() {
        filter_data["phrase"] = $('#search').val();
        reset_content();
        hide_list();
    }, 300);
}

var templates = {
    loading: function() {
        return `<div class="loading">Wczytywanie...</div>`;
    },
    no_projects: function() {
        return `
        <div class="no-projects">
            Nie ma już nic więcej do wyświetlenia
            <button onclick="reset_content()" class="button-secondary-big w-100">Odśwież</button>
        </div>
        `;
    },
    project_item: function(data) {
        var active = '';
        if(selected.type == "project") {
            if(selected.id == data["id"]) {
                $('div.active[data-project-id]').removeClass('active');
                active = ' active';
            }
        }
        var color = data["priority"] == 0 ? 'transparent' : priorities[data["priority"]]["color"];
        return `
        <div class="project-item${active}" style="border-left-color: ${color}" onclick="load_content('show-project', ${data["id"]})" data-project-id="${data["id"]}" data-project-end="${data["end_date"] ? 'true' : 'false'}" data-choose-element>
            <div class="title-row">
                <div class="title">
                    ${data["name"]}
                </div>
                <div class="date">
                    ${data["start_date"].slice(0,10)}
                </div>
            </div>
            <div class="description">
                ${data["intro"] ? data["intro"] : "Brak opisu"}
            </div>
        </div>
        `;
    },
    project: function(url) {
        return `
        <iframe class="show" id="project-iframe" src="project?id=${url}"></iframe>
        `;
    },
    profile: function(url) {
        return `
        <iframe class="show" src="profile?id=${url}"></iframe>
        `;
    },
    page: function(url) {
        return `
        <iframe class="show" src="${url}"></iframe>
        `;
    },
    add_project: function() {
        return `
        <iframe class="show" id="save-project-iframe" src="add-project"></iframe>
        `;
    },
    edit_project: function(id) {
        return `
        <iframe class="show" id="save-project-iframe" src="edit-project?id=${id}"></iframe>
        `;
    }
};