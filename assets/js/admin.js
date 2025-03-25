jQuery(document).ready(function($){
    // When the Template or Sender dropdown changes, update the preview.
    $('#cem_template_select, #cem_sender_select').on('change', function(){
        var templateID = $('#cem_template_select').val();
        var senderData = $('#cem_sender_select option:selected').data('jobtitle') || '';
        if(templateID) {
            $.post(cem_ajax_obj.ajax_url, { action: 'cem_get_template_details', template_id: templateID }, function(response){
                if(response.success) {
                    var template = response.data;
                    // Build a title using the event name and request type.
                    var titleText = template.event_name + ' ' + template.request_type;
                    var content = template.template_content;
                    // Replace [Sender Name] and [Sender Role] placeholders.
                    content = content.replace('[Sender Name]', $('#cem_sender_select option:selected').text());
                    content = content.replace('[Sender Role]', senderData);
                    $('#cem_email_title').text(titleText);
                    $('#cem_email_content').html(content);
                }
            });
        }
    });
});
