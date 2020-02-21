$(document).ready(function () {
    let permissionTree = $("#permission-tree");
    /**
     * Permission tree
     */
    permissionTree.kendoTreeView({
        checkboxes: {
            checkChildren: true
        },
        check: onCheck,
        dataSource: permissionData
    });
    // function that gathers IDs of checked nodes
    function checkedNodeIds(treeView, nodes, checkedNodes) {
        for (var i = 0; i < nodes.length; i++) {
            if (nodes[i].checked) {
                let parentNode = nodes[i].parent().parent();
                if (parentNode !== undefined) {
                    let parentNodeId = parentNode.id;
                    if (nodes[i].id === parentNodeId + '-create' ||
                        nodes[i].id === parentNodeId + '-edit' ||
                        nodes[i].id === parentNodeId + '-delete' ||
                        nodes[i].id === parentNodeId + '-view'
                    ) {
                        let thisNode = nodes[i].parent().parent().parent();
                        if (thisNode[0].id !== undefined) {
                            if (thisNode[0].id.includes('-view')) {
                                let item = treeView.dataSource.get( thisNode.parent().parent().parent().id + '-view');
                                if (item !== undefined) {
                                    item.set('checked', true);
                                }
                                thisNode[0].set('checked', true)
                            }
                        }
                        let item = treeView.dataSource.get( parentNodeId + '-view');
                        item.set('checked', true);
                        if (!checkedNodes.includes(item.id)) { checkedNodes.push(item.id); }
                    }
                }
                if (!checkedNodes.includes(nodes[i].id)) {
                    checkedNodes.push(nodes[i].id);
                }
            }

            if (nodes[i].hasChildren) {
                checkedNodeIds(treeView, nodes[i].children.view(), checkedNodes);
            }
        }
    }
    // show checked node IDs on datasource change
    function onCheck() {
        checkedNodes = [];
        var treeView = $("#permission-tree").data("kendoTreeView"),
            message;
        checkedNodeIds(treeView, treeView.dataSource.view(), checkedNodes);

        if (checkedNodes.length > 0) {
            message = "IDs of checked nodes: " + checkedNodes.join(",");
        } else {
            message = "No nodes checked.";
        }
        $('#permissions').val(checkedNodes);
        $("#result").html(message);
    }
    let treeView = permissionTree.data("kendoTreeView");
    if (checkedNodes.length > 0) {
        checkedNodes.forEach(function (item) {
            let node = treeView.dataSource.get(item);
            if (node) {
                node.set('checked', true);
            }
        });
        $('#permissions').val(checkedNodes);
    }

    /**
     * User table
     */
    let langText = $('.lang-text'),
        model = 'users',
        modelDataTable = $(`#${model}_data`),
        mainLang = langText.attr('main-lang');
    let table = modelDataTable.DataTable({
        initComplete: function (settings, json) {
            let dataWrapper = $(`#${model}_data_wrapper`),
                selectDropdown = dataWrapper.find(`#${model}_data_length`),
                inputFilter = dataWrapper.find(`#${model}_data_filter`),
                dropdown = selectDropdown.find(`select`) || null,
                filterInput = inputFilter.find(`input`) || null;
            dropdown.addClass('dmovie-textbox-border h-32 p-l-10');
            filterInput.addClass('dmovie-textbox-border h-32 p-l-10');
            // console.log(dropdown);
        },
        columnDefs: [
            {
                targets: 0,
                orderable: false,
                width: '2%',
            },
            {
                targets: 2,
                width: '15%'
            },
            {
                targets: 'no-sort',
                orderable: false
            }
        ],
        order: [
            [1, 'desc']
        ],
        oLanguage: {
            sUrl: `/adminhtml/assets/plugins/datatables/i18n/${mainLang}.json`
        }
    });
    let usersSelect = $('#users_select');
    $('.user-checkbox').on('change', function () {
        let self = $(this),
            userId = self.attr('data-id');
        if (self.is(':checked')) {
            let option = `<option scope="users" value="${userId}" selected>${userId}</option>`;
            usersSelect.append(option);
        } else {
            $('option[scope="users"]').each(function (index, value) {
                let option = $(value),
                    optionVal = option.val(),
                    currentVal = userId;
                if (optionVal === currentVal) {
                    option.remove();
                }
            });
        }
    });
});
