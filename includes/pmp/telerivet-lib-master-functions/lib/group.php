<?php

/**    
    Telerivet_Group
    
    Represents a group used to organize contacts within Telerivet.
    
    Fields:
    
      - id (string, max 34 characters)
          * ID of the group
          * Read-only
      
      - name
          * Name of the group
          * Updatable via API
      
      - dynamic (bool)
          * Whether this is a dynamic or normal group
          * Read-only
      
      - num_members (int)
          * Number of contacts in the group (null if the group is dynamic)
          * Read-only
      
      - time_created (UNIX timestamp)
          * Time the group was created in Telerivet
          * Read-only
      
      - vars (associative array)
          * Custom variables stored for this group
          * Updatable via API
      
      - project_id
          * ID of the project this group belongs to
          * Read-only
    
    Example Usage:
    -------------
    
    $PROJECT_ID = 'YOUR_PROJECT_ID'; // from https://telerivet.com/dashboard/api   
    
    $project = $telerivet->getProjectById($PROJECT_ID); 
    
    $group = $project->getOrCreateGroup("Subscribers");
    
    echo $group->num_members;
    
    $contact = $project->getOrCreateContact(array('phone_number' => '555-0123'));
    $contact->addToGroup($group);
    
 */
class Telerivet_Group extends Telerivet_Entity
{
    /**
        $group->queryContacts($options)
        
        Queries contacts that are members of the given group.
        
        Arguments:
          - $options (associative array)
            
            - name
                * Filter contacts by name
                * Allowed modifiers: name[ne], name[prefix], name[not_prefix], name[gte], name[gt],
                    name[lt], name[lte]
            
            - phone_number
                * Filter contacts by phone number
                * Allowed modifiers: phone_number[ne], phone_number[prefix],
                    phone_number[not_prefix], phone_number[gte], phone_number[gt], phone_number[lt],
                    phone_number[lte]
            
            - time_created (UNIX timestamp)
                * Filter contacts by time created
                * Allowed modifiers: time_created[ne], time_created[min], time_created[max]
            
            - last_message_time (UNIX timestamp)
                * Filter contacts by last time a message was sent or received
                * Allowed modifiers: last_message_time[exists], last_message_time[ne],
                    last_message_time[min], last_message_time[max]
            
            - last_incoming_message_time (UNIX timestamp)
                * Filter contacts by last time a message was received
                * Allowed modifiers: last_incoming_message_time[exists],
                    last_incoming_message_time[ne], last_incoming_message_time[min],
                    last_incoming_message_time[max]
            
            - last_outgoing_message_time (UNIX timestamp)
                * Filter contacts by last time a message was sent
                * Allowed modifiers: last_outgoing_message_time[exists],
                    last_outgoing_message_time[ne], last_outgoing_message_time[min],
                    last_outgoing_message_time[max]
            
            - incoming_message_count (int)
                * Filter contacts by number of messages received from the contact
                * Allowed modifiers: incoming_message_count[ne], incoming_message_count[min],
                    incoming_message_count[max]
            
            - outgoing_message_count (int)
                * Filter contacts by number of messages sent to the contact
                * Allowed modifiers: outgoing_message_count[ne], outgoing_message_count[min],
                    outgoing_message_count[max]
            
            - send_blocked (bool)
                * Filter contacts by blocked status
            
            - vars (associative array)
                * Filter contacts by value of a custom variable (e.g. vars[email], vars[foo], etc.)
                * Allowed modifiers: vars[foo][exists], vars[foo][ne], vars[foo][prefix],
                    vars[foo][not_prefix], vars[foo][gte], vars[foo][gt], vars[foo][lt], vars[foo][lte],
                    vars[foo][min], vars[foo][max]
            
            - sort
                * Sort the results based on a field
                * Allowed values: default, name, phone_number, last_message_time
                * Default: default
            
            - sort_dir
                * Sort the results in ascending or descending order
                * Allowed values: asc, desc
                * Default: asc
            
            - page_size (int)
                * Number of results returned per page (max 500)
                * Default: 50
            
            - offset (int)
                * Number of items to skip from beginning of result set
                * Default: 0
          
        Returns:
            Telerivet_APICursor (of Telerivet_Contact)
    */
    function queryContacts($options = null)
    {
        return $this->_api->newApiCursor('Telerivet_Contact', "{$this->getBaseApiPath()}/contacts", $options);
    }

    /**
        $group->queryScheduledMessages($options)
        
        Queries scheduled messages to the given group.
        
        Arguments:
          - $options (associative array)
            
            - message_type
                * Filter scheduled messages by message_type
                * Allowed values: sms, mms, ussd, call, service
            
            - time_created (UNIX timestamp)
                * Filter scheduled messages by time_created
                * Allowed modifiers: time_created[ne], time_created[min], time_created[max]
            
            - next_time (UNIX timestamp)
                * Filter scheduled messages by next_time
                * Allowed modifiers: next_time[exists], next_time[ne], next_time[min],
                    next_time[max]
            
            - sort
                * Sort the results based on a field
                * Allowed values: default, name
                * Default: default
            
            - sort_dir
                * Sort the results in ascending or descending order
                * Allowed values: asc, desc
                * Default: asc
            
            - page_size (int)
                * Number of results returned per page (max 500)
                * Default: 50
            
            - offset (int)
                * Number of items to skip from beginning of result set
                * Default: 0
          
        Returns:
            Telerivet_APICursor (of Telerivet_ScheduledMessage)
    */
    function queryScheduledMessages($options = null)
    {
        return $this->_api->newApiCursor('Telerivet_ScheduledMessage', "{$this->getBaseApiPath()}/scheduled", $options);
    }

    /**
        $group->save()
        
        Saves any fields that have changed for this group.
    */
    function save()
    {
        parent::save();
    }

    /**
        $group->delete()
        
        Deletes this group (Note: no contacts are deleted.)
    */
    function delete()
    {
        $this->_api->doRequest("DELETE", "{$this->getBaseApiPath()}");
    }

    function getBaseApiPath()
    {
        return "/projects/{$this->project_id}/groups/{$this->id}";
    }
}
