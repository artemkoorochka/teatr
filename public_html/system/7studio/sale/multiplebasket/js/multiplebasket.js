
var multiplebasket = {

    procedures: {
        add: "/system/7studio/sale/multiplebasket/procedures/add.php"
    },

    add: function () {

        alert("add");

        $.get(this.procedures.add, {}, function (result) {
            alert("result");
        });



        return false;
    }
};