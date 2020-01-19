$(document).ready(function () {
    /**
     * Permission tree
     */
    $("#permission-tree").kendoTreeView({
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
                        nodes[i].id === parentNodeId + '-delete') {
                        let item = treeView.dataSource.get( parentNodeId + '-view');
                        item.set('checked', true);
                        if (!checkedNodes.includes(item.id)) { checkedNodes.push(item.id); }
                    }
                }
                checkedNodes.push(nodes[i].id);
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
    let treeView = $("#permission-tree").data("kendoTreeView");
    if (checkedNodes.length > 0) {
        checkedNodes.forEach(function (item) {
            let node = treeView.dataSource.get(item);
            if (node) {
                node.set('checked', true);
            }
        });
        $('#permissions').val(checkedNodes);
    }

});
