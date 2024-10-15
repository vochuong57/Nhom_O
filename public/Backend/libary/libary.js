(function($){

    var $document = $(document)
    
    var HT={};
    var _token=$('meta[name="csrf-token"]').attr('content');

    HT.switchery=()=>{
        $('.js-switch').each(function(){
            var switchery = new Switchery(this, { color: '#1AB394', size: 'small' });
        })
    }
    
    HT.select2=()=>{
        if($('.setupSelect2').length){
            $('.setupSelect2').select2();
        }
    }

    HT.checkAll=()=>{
        if($('#checkAll').length){
            $(document).on('change', '#checkAll', function(){
                let isChecked=$(this).prop('checked')
                $('.checkBoxItem').prop('checked', isChecked);
                $('.checkBoxItem').each(function(){
                    let _this=$(this)
                    if(_this.prop('checked')){
                        _this.closest('tr').addClass('active-bg')
                    }else{
                        _this.closest('tr').removeClass('active-bg')
                    }
                })
            })
        }
    }

    HT.checkBoxItem=()=>{
        if($('.checkBoxItem').length){
            $(document).on('change','.checkBoxItem', function(){
                let _this=$(this)
                let isChecked=_this.prop('checked')
                if(isChecked){
                    _this.closest('tr').addClass('active-bg')
                }else{
                    _this.closest('tr').removeClass('active-bg')
                }
                HT.allChecked()
            })
        }
    }

    HT.allChecked=()=>{
        let allChecked=$('.checkBoxItem:checked').length===$('.checkBoxItem').length;
        $('#checkAll').prop('checked', allChecked);
    }

    HT.deleteAll = () => {
        if ($('.deleteAll').length) {
            $(document).on('click', '.deleteAll', function(e) {
                e.preventDefault();
                let _this = $(this);
                let id = [];
                let hasAdminUser = false;
                $('.checkBoxItem').each(function() {
                    let checkBox = $(this)
                    if (checkBox.prop('checked')) {
                        id.push(checkBox.val())
                        let userCatalogueId = checkBox.data('catalogue-id');
                        if (userCatalogueId == 1) {
                            hasAdminUser = true;
                        }
                    }
                })
                if (hasAdminUser) {
                    alert('Bạn đã chọn nhầm thành viên thuộc nhóm quản trị viên. Hãy chọn lại.');
                    return;
                }
                let option = {
                    'model': _this.attr('data-model'),
                    'id': id,
                    '_token': _token
                }
                $.ajax({
                    url: 'ajax/dashboard/deleteAll',
                    type: 'POST',
                    data: option,
                    dataType: 'json',
                    success: function(res) {
                        console.log(res);
                        if (res.flag == true) {
                            for (let i = 0; i < id.length; i++) {
                                $('.rowdel-' + id[i]).remove();
                            }
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('Lỗi: ' + jqXHR);
                        console.log('Lỗi request: ' + textStatus);
                        console.log('Lỗi nội dung: ' + errorThrown);
                    }
                });
            })
        }
    } 

    HT.changeStatus = () => {
        if ($('.status').length) {
            $(document).on('change', '.status', function(){
                let _this=$(this);
                let currentValue = _this.val(); 
                let option={
                    'value': currentValue,
                    'modelId': _this.attr('data-modelId'),
                    'model': _this.attr('data-model'),
                    'field': _this.attr('data-field'),
                    '_token': _token
                }
                console.log(option)
                $.ajax({
                    url: getStatusUrl,
                    type: 'POST',
                    data: option,
                    dataType: 'json',
                    success: function(res){
                        console.log(res);
                        currentValue = currentValue == 1 ? 2 : 1;
                        _this.val(currentValue);
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                        console.log('Lỗi: '+jqXHR);
                        console.log('Lỗi request: '+ textStatus);
                        console.log('Lỗi nội dung: '+ errorThrown);
                    }
                });
            })  
        }
    }

    HT.setupDatePicker = () => {
        $('.datepicker input').datetimepicker({
            timepicker: true,
            format: 'd/m/Y',
            // value: new Date(),
            maxDate: new Date(),
        });
    
        $('.span-icon-calendar').on('click', function() {
            $(this).closest('.datepicker').find('input').focus();
        });
    };
    

    $document.ready(function(){
        HT.select2();
        HT.checkAll();
        HT.checkBoxItem();
        HT.deleteAll();
        HT.setupDatePicker();
        HT.switchery();
        HT.changeStatus();
    })

})(jQuery)