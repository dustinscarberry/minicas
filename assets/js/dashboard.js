import '@babel/polyfill';
import '../scss/dashboard.scss';
import axios from 'axios';

$(document).ready(function(){
  //nav pin button
  $('.dashboard-nav-pin-btn').click(function(){
    $('body').toggleClass('nav-pin');

    if ($('body').hasClass('nav-pin'))
      document.cookie = "navPin=true;";
    else
      document.cookie = "navPin=; expires=Thu, 01 Jan 1970 00:00:00 UTC;";
  });

  //nav submenus
  $('.has-submenu').click(function(){
    $('.has-submenu').not(this).removeClass('is-open');
    $(this).toggleClass('is-open');
    return false;
  });

  //profile menu
  $('.profile-avatar').click(function(){
    $(this).parent().toggleClass('is-open');
    event.stopPropagation();
  });

  $('.profile-menu').click(function(){
    event.stopPropagation();
  });

  //tooltips
  tippy('.tooltip', {
    placement: 'right',
    arrow: true,
    delay: 500
  });

  //body offclick shared function
  $('body').click(function(e){
    $('.dashboard-profile').removeClass('is-open');
    $('.editable-text-wrapper').removeClass('is-unlocked');
    $('.editable-text').prop('disabled', 'disabled');
  });

  //close feedback by user
  $('.feedback-close-btn').on('click', function(){
    $(this).closest('.feedback-wrapper').removeClass('visible');
  });

  //close feedback on timer
  setTimeout(function(){
    $('.feedback-wrapper').removeClass('visible');
  }, 5000);

  $('.feedback-wrapper').addClass('visible');

  // active session details toggle
  $('.active-session-toggledetails').click(function(){
    $(this).toggleClass('is-open');
  });

  // active session service details toggle
  $('.active-session-service').click(function(){
    event.stopPropagation();
    $(this).toggleClass('is-open');
  });

  // invalid form fields
  formValidate();
  function formValidate()
  {
    const invalidClassName = 'invalid';
    const inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(function (input)
    {
      // Add a css class on submit when the input is invalid.
      input.addEventListener('invalid', ()  => {
        input.classList.add(invalidClassName);
      });

      // Remove the class when the input becomes valid.
      input.addEventListener('input', () => {
        if (input.validity.valid)
          input.classList.remove(invalidClassName);
        else
          input.classList.add(invalidClassName);
      });
    });
  }

  const formSubmitButtons = document.querySelectorAll('input[type=submit], button[type=submit]');
  formSubmitButtons.forEach(function (input)
  {
    input.addEventListener('click', () => {
      formValidate();
    });
  });

  const identityProviderViewDataTable = $('#identity-provider-view-table').DataTable({
    paging: false,
    info: false,
    columnDefs: [{
      targets: 1,
      orderable: false
    }],
    order: [],
    language: {
      emptyTable: 'No Identity Providers created'
    },
    initComplete: function() {
      $(this).removeClass('is-hidden');
    }
  });

  const serviceProviderViewDataTable = $('#service-provider-view-table').DataTable({
    paging: false,
    info: false,
    columnDefs: [{
      targets: 2,
      orderable: false
    }],
    order: [],
    language: {
      emptyTable: 'No Service Providers created'
    },
    initComplete: function() {
      $(this).removeClass('is-hidden');
    }
  });

  const userViewDataTable = $('#user-view-table').DataTable({
    paging: false,
    info: false,
    columnDefs: [{
      targets: 2,
      orderable: false
    }],
    order: [],
    language: {
      emptyTable: 'No users created, you shouldn\'t be able to see this....'
    },
    initComplete: function() {
      $(this).removeClass('is-hidden');
    }
  });

  const attributeViewDataTable = $('#attribute-view-table').DataTable({
    paging: false,
    info: false,
    columnDefs: [{
      targets: 2,
      orderable: false
    }],
    order: [[0, 'asc']],
    language: {
      emptyTable: 'No attributes available'
    },
    initComplete: function() {
      $(this).removeClass('is-hidden');
    }
  });

  const serviceCategoriesViewDataTable = $('#servicecategories-view-table').DataTable({
    paging: false,
    info: false,
    columnDefs: [{
      targets: 1,
      orderable: false
    }],
    order: [[0, 'asc']],
    language: {
      emptyTable: 'No categories available'
    },
    initComplete: function() {
      $(this).removeClass('is-hidden');
    }
  });

  const activeSessionsViewDataTable = $('#active-session-view-table').DataTable({
    paging: false,
    info: false,
    columnDefs: [{
      targets: 1,
      width: '200px'
    },{
      targets: 2,
      width: '200px'
    },{
      targets: 3,
      orderable: false,
      width: '150px'
    },
  ],
    order: [[1, 'desc']],
    language: {
      emptyTable: 'No current active user sessions'
    },
    initComplete: function() {
      $(this).removeClass('is-hidden');
    },
    autoWidth: false
  });

  const invalidServiceViewDataTable = $('#invalid-service-view-table').DataTable({
    paging: false,
    info: false,
    order: [[2, 'desc']],
    language: {
      emptyTable: 'No invalid services accessed'
    },
    initComplete: function() {
      $(this).removeClass('is-hidden');
    }
  });

  //delete records ajax
  $('#user-view-table').on('click', '.btn-delete', function(){
    confirmDelete(
      async () => {
        let parent = $(this).closest('tr');
        let dataID = parent.data('id');

        let rsp = await axios.delete('/api/v1/users/' + dataID);

        if (rsp.status == 200 && !rsp.data.error)
        {
          parent.addClass('is-deleting');
          setTimeout(function(){
            userViewDataTable.row(parent).remove().draw();
          }, 200);
        }
      }
    );
  });

  $('#service-provider-view-table').on('click', '.btn-delete', function(){
    confirmDelete(
      async () => {
        let parent = $(this).closest('tr');
        let dataID = parent.data('id');

        let rsp = await axios.delete('/api/v1/serviceproviders/' + dataID);

        if (rsp.status == 200 && !rsp.data.error)
        {
          parent.addClass('is-deleting');
          setTimeout(function(){
            serviceProviderViewDataTable.row(parent).remove().draw();
          }, 200);
        }
      }
    );
  });

  $('#identity-provider-view-table').on('click', '.btn-delete', function(){
    confirmDelete(
      async () => {
        let parent = $(this).closest('tr');
        let dataID = parent.data('id');

        let rsp = await axios.delete('/api/v1/identityproviders/' + dataID);

        if (rsp.status == 200 && !rsp.data.error)
        {
          parent.addClass('is-deleting');
          setTimeout(function(){
            identityProviderViewDataTable.row(parent).remove().draw();
          }, 200);
        }
      }
    );
  });

  $('#attribute-view-table').on('click', '.btn-delete', function(){
    confirmDelete(
      async () => {
        let parent = $(this).closest('tr');
        let dataID = parent.data('id');

        let rsp = await axios.delete('/api/v1/attributes/' + dataID);

        if (rsp.status == 200 && !rsp.data.error)
        {
          parent.addClass('is-deleting');
          setTimeout(function(){
            attributeViewDataTable.row(parent).remove().draw();
          }, 200);
        }
      }
    );
  });

  $('#active-session-view-table').on('click', '.btn-delete', function(){
    confirmDelete(
      async () => {
        let parent = $(this).closest('tr');
        let dataID = parent.data('id');

        let rsp = await axios.delete('/api/v1/sessions/' + dataID);

        if (rsp.status == 200 && !rsp.data.error)
        {
          parent.addClass('is-deleting');
          setTimeout(function(){
            activeSessionsViewDataTable.row(parent).remove().draw();
          }, 200);
        }
      }
    );
  });

  $('#servicecategories-view-table').on('click', '.btn-delete', function(){
    confirmDelete(
      async () => {
        let parent = $(this).closest('tr');
        let dataID = parent.data('id');

        let rsp = await axios.delete('/api/v1/servicecategories/' + dataID);

        if (rsp.status == 200 && !rsp.data.error)
        {
          parent.addClass('is-deleting');
          setTimeout(function(){
            serviceCategoriesViewDataTable.row(parent).remove().draw();
          }, 200);
        }
      }
    );
  });

  //create collections
  let serviceAttributeMappingsCollection = new ServiceCollection('#service-attribute-mappings-group', 3);

  //add delete button to collection item forms
  $('.collection-subform .row').not('.collection-header').each(function(){
    $(this).append('<button class="collection-delete-btn"></button>');
  });

  //add delete button click event
  $('.collection-subform').on('click', '.collection-delete-btn', function(){
    $(this).parent().remove();
  });




});

function confirmDelete(success, cancel)
{
  $.confirm({
    title: 'Are you sure?',
    content: 'Delete this item',
    type: 'red',
    theme: 'supervan',
    buttons: {
      ok: {
        text: 'Delete!',
        btnClass: 'btn-red',
        action: function() {
          if (success)
            success();
        }
      },
      cancel: {
        text: 'Cancel',
        action: function() {
          if (cancel)
            cancel();
        }
      }
    }
  });
}

class ServiceCollection
{
  constructor(collectionGroupNode, columns)
  {
    this.collectionGroup = $(collectionGroupNode);
    this.columns = columns;
    this.collectionSubForm = this.collectionGroup.find('.collection-subform');
    this.collectionSubForm.data('index', this.collectionSubForm.find('.form-control').length);

    this.collectionGroup.on('click', '.collection-add-btn', () => {
      this.addForm();
      return false;
    });
  }

  addForm()
  {
    let prototype = this.collectionSubForm.data('prototype');
    let index = this.collectionSubForm.data('index');

    this.collectionSubForm.data('index', index + 1);

    //replace __name__ with index
    prototype = prototype.replace(/__name__/g, index);

    /*
    if (this.columns == 3)
      prototype = prototype.replace(/form-group/g, 'form-group col-sm-4');
    else if (this.columns == 2)
      prototype = prototype.replace(/form-group/g, 'form-group col-sm-6');
    else if (this.columns == 1)
      prototype = prototype.replace(/form-group/g, 'form-group col-sm-12');
    */

    //add [row] class
    let formRow = $(prototype).addClass('row');

    //add row deletion button
    formRow.append('<button class="collection-delete-btn"></button>');

    //add extra custom  attributes if needed to subform row
    formRow = this.addExtraAttributes(formRow);

    //append to collection list
    this.collectionSubForm.append(formRow);
  }

  addExtraAttributes(formRow)
  {
    const subformId = this.collectionSubForm.parent().attr('id');

    //extra actions for updates
    if (subformId == 'maintenance-updates-group'
      || subformId == 'incident-updates-group'
    )
    {
      const currentDatetime = moment().format('MM/DD/YYYY h:mm A');
      const field = formRow.find('.editable-text');

      //set datetimepicker because .on doesn't work for new ones, for some reason
      field.datetimepicker();
      //set starting value of field
      field.val(currentDatetime);
    }

    return formRow;
  }
}
