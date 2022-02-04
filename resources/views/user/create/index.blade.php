<?php
  $disable = [
    'navbar',
    'header'
  ];
?>
@extends('layout.app')
@section('content')
  <section>
    <div class="container py-4">
      <div class="row">
        <div class="col-lg-7 mx-auto d-flex justify-content-center flex-column">
          <div class="card d-flex justify-content-center p-4 shadow-lg">
            <div class="text-center">
              <h3 class="text-gradient text-primary">Cadastro</h3>
              <p class="mb-0">Falta pouco para finalizar o seu cadastro.</p>
            </div>
            <div class="card card-plain">
              <form
                role="form"
                id="register-form"
                method="post"
                action="{{ route('user.store') }}"
                enctype="multipart/form-data"
              >
                {{ csrf_field() }}
                <input type="hidden" name="email" value="{{ $user->email }}"/>
                <div class="card-body pb-2">
                  @include('user.create.pagination')

                  @include('user.create.steps.info')
                  @include('user.create.steps.availability')
                  @include('user.create.steps.ability')
                  @include('user.create.steps.ministry')
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
@section('scripts')
  <script src="https://igorescobar.github.io/jQuery-Mask-Plugin/js/jquery.mask.min.js"></script>
  <script>
    $(function() {
      $("#info-whatsapp").mask("(00) 00000-0000");
      $('#register-form').on("submit",validateContentMinistry);
    });
    const pages = [
      'content-info',
      'content-availability',
      'content-ability',
      'content-ministry'
    ];
    var pagesValidated = [];

    function handlePagination(to){
      pagesValidated = pagesValidated.filter(pages => pages != $('.to-page-contents.active').attr('id').substr(3));
      switch(to){
        case 2: if(!validateContentInfo()) return; break;
        case 3: if(!validateContentAvailibility()) return; break;
        case 4: if(!validateContentAbility()) return; break;
      }
      $('.page-contents').hide();
      $(`#${pages[to - 1]}`).show('slow');
      $('.to-page-contents').removeClass('active');
      $(`#to-${pages[to - 1]}`).removeClass('disabled').addClass('active');
      $('html').scrollTop(0);
    }
    function toggleAvailability(elem, weekday){
      elem.toggleClass('bg-gradient-light bg-gradient-primary');

      let availabilities = $('#input-availability').val() ? $('#input-availability').val().split(',') : [];
      if(availabilities.includes(weekday)) availabilities = availabilities.filter(
        item => item != weekday
      );
      else availabilities.push(weekday);
      $('#input-availability').val(availabilities.join(','));
    }
    function toggleMinistry(elem, id){
      elem.toggleClass('bg-secondary bg-primary');

      let ministries = $('#ministries_ids').val() ? $('#ministries_ids').val().split(',') : [];
      if(ministries.includes(id)) ministries = ministries.filter(
        item => item != id
      );
      else ministries.push(id);
      $('#ministries_ids').val(ministries.join(','));
    }

    function validateContentInfo(){
      // NAME
      if(!notifyWithFocusWhenHasError({
        elem: $("#info-name"),
        rules: {
          minLen: { value: 5, message: 'O nome deve conter no mínimo 5 caracteres'},
          maxLen: { value: 80, message: 'O nome deve conter no máximo 80 caracteres'}
        },
        messageDefault: 'O nome é obrigatório'
      })) return false;
      // WHATSAPP
      let whatsappRule = {
        value: 15,
        message: 'O número de whatsapp deve ter 15 caracteres, seguindo o padrao (00) 00000-0000'
      };
      if(!notifyWithFocusWhenHasError({
        elem: $("#info-whatsapp"),
        rules: {
          minLen: whatsappRule, maxLen: whatsappRule,
        },
        messageDefault: 'O número de whatsapp é obrigatório'
      })) return false;
      // PASSWORD
      if(!notifyWithFocusWhenHasError({
        elem: $("#info-password"),
        rules: {
          minLen: { value: 6, message: 'A senha deve conter no mínimo 6 caracteres'},
          maxLen: { value: 12, message: 'A senha deve conter no máximo 12 caracteres'}
        },
        messageDefault: 'A senha é obrigatória'
      })) return false;
      if(!notifyWithFocusWhenHasError({
        elem: $("#info-conf-password"),
        rules: {
          equal: { value: $("#info-password").val(), message: 'A senha e a confirmação de senha estão diferentes' },
        },
        messageDefault: 'A confirmação de senha é obrigatória'
      })) return false;

      pagesValidated.push('content-info');
      
      return true;
    }
    function validateContentAvailibility(){
      pagesValidated.push('content-availability');
      return true;
    }
    function validateContentAbility(){
      pagesValidated.push('content-ability');
      return true;
    }
    function validateContentMinistry(e){
      e.preventDefault();
      
      if(pagesValidated.length < 3){
        let index = pagesValidated.length + 1;
        if(index < pages.length){
          if(!pagesValidated.includes(pages[index])){
            handlePagination(index + 1);
            return false;
          }
        }
        notify(
          'danger',
          'É obrigatório concluir todas as etapas do cadastro.<br/>Reveja o preenchimento!'
        );
        return false;
      }

      if($('.card-ministry').length > 0){
        if($('#ministries_ids').val().length === 0){
          notify('danger', 'Selecione pelo menos um ministério');
          return false;
        }
      }

      pagesValidated.push('content-ministry');

      e.target.submit();
      return true;
    }
    // UTILS
    function validateRequired(val, rules, messageDefault = 'Este campo é obrigatório'){
      if(!val) return { result: false, response: messageDefault };
      if(rules.minLen && val.length < rules.minLen.value) return {
        result: false,
        response: rules.minLen.message ?? `Este campo deve conter no mínimo ${rules.minLen.value} caracteres`
      };
      if(rules.maxLen && val.length > rules.maxLen.value) return {
        result: false,
        response: rules.maxLen.message ?? `Este campo deve conter no máximo ${rules.maxLen.value} caracteres`
      };
      if(rules.equal && val !== rules.equal.value) return {
        result: false,
        response: rules.equal.message ?? `Valor incorreto`
      };

      return { result: true, response: 'Verificado'};
    }
    function focusInError(elem, timeout = 6000){
      elem.addClass('is-invalid').focus();
      setTimeout(() => elem.removeClass('is-invalid'), timeout);
    }
    function notifyWithFocusWhenHasError(obj){
      let res = validateRequired(obj.elem.val(),obj.rules, obj.messageDefault);
      if(!res.result){
        notify('warning', res.response);
        focusInError(obj.elem);
        return false;
      }
      return true;
    }
  </script>
  @include('utils.scripts.mirror-image')
@endsection