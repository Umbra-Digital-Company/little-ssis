<?php

/**
    Telerivet_Service
    
    Represents an automated service on Telerivet, for example a poll, auto-reply, webhook
    service, etc.
    
    A service, generally, defines some automated behavior that can be
    invoked/triggered in a particular context, and may be invoked either manually or when a
    particular event occurs.
    
    Most commonly, services work in the context of a particular message, when
    the message is originally received by Telerivet.
    
    Fields:
    
      - id (string, max 34 characters)
          * ID of the service
          * Read-only
      
      - name
          * Name of the service
          * Updatable via API
      
      - active (bool)
          * Whether the service is active or inactive. Inactive services are not automatically
              triggered and cannot be invoked via the API.
          * Updatable via API
      
      - priority (int)
          * A number that determines the order that services are triggered when a particular
              event occurs (smaller numbers are triggered first). Any service can determine whether
              or not execution "falls-through" to subsequent services (with larger priority values)
              by setting the return_value variable within Telerivet's Rules Engine.
          * Updatable via API
      
      - contexts (associative array)
          * A key/value map where the keys are the names of contexts supported by this service
              (e.g. message, contact), and the values are themselves key/value maps where the keys
              are event names and the values are all true. (This structure makes it easy to test
              whether a service can be invoked for a particular context and event.)
          * Read-only
      
      - vars (associative array)
          * Custom variables stored for this service
          * Updatable via API
      
      - project_id
          * ID of the project this service belongs to
          * Read-only
      
      - label_id
          * ID of the label containing messages sent or received by this service (currently only
              used for polls)
          * Read-only
      
      - response_table_id
          * ID of the data table where responses to this service will be stored (currently only
              used for polls)
          * Read-only
      
      - sample_group_id
          * ID of the group containing contacts that have been invited to interact with this
              service (currently only used for polls)
          * Read-only
      
      - respondent_group_id
          * ID of the group containing contacts that have completed an interaction with this
              service (currently only used for polls)
          * Read-only
      
      - questions (array)
          * Array of objects describing each question in a poll (only used for polls). Each
              object has the properties `"id"` (the question ID), `"content"` (the text of the
              question), and `"question_type"` (either `"multiple_choice"`, `"missed_call"`, or
              `"open"`).
          * Read-only
 */
class Telerivet_Service extends Telerivet_Entity
{
    /**
        $service->invoke($options)
        
        Manually invoke this service in a particular context.
        
        For example, to send a poll to a particular contact (or resend the
        current question), you can invoke the poll service with context=contact, and `contact_id` as
        the ID of the contact to send the poll to. (To trigger a service to multiple contacts, use
        [project.sendBroadcast](#Project.sendBroadcast). To schedule a service in the future, use
        [project.scheduleMessage](#Project.scheduleMessage).)
        
        Or, to manually apply a service for an incoming message, you can
        invoke the service with `context`=`message`, `event`=`incoming_message`, and `message_id` as
        the ID of the incoming message. (This is normally not necessary, but could be used if you
        want to override Telerivet's standard priority-ordering of services.)
        
        Arguments:
          - $options (associative array)
              * Required
            
            - context
                * The name of the context in which this service is invoked
                * Allowed values: message, call, contact, project
                * Required
            
            - event
                * The name of the event that is triggered (must be supported by this service)
                * Default: default
            
            - message_id
                * The ID of the message this service is triggered for
                * Required if context is 'message'
            
            - contact_id
                * The ID of the contact this service is triggered for
                * Required if context is 'contact'
          
        Returns:
            object
     */
    function invoke($options)
    {
        $invoke_result = $this->_api->doRequest('POST', $this->getBaseApiPath() . '/invoke', $options);
        
        if ($invoke_result['sent_messages'])
        {
            $sent_messages = array();
            foreach ($invoke_result['sent_messages'] as $sent_message_data)
            {
                $sent_messages[] = new Telerivet_Message($this->api, $sent_message_data);
            }
            $invoke_result['sent_messages'] = $sent_messages;
        }        
        return $invoke_result;
    }    
    
    /**
        $service->queryContactStates($options)
        
        Query the current states of contacts for this service.
        
        Arguments:
          - $options (associative array)
            
            - id
                * Filter states by id
                * Allowed modifiers: id[ne], id[prefix], id[not_prefix], id[gte], id[gt], id[lt],
                    id[lte]
            
            - vars (associative array)
                * Filter states by value of a custom variable (e.g. vars[email], vars[foo], etc.)
                * Allowed modifiers: vars[foo][exists], vars[foo][ne], vars[foo][prefix],
                    vars[foo][not_prefix], vars[foo][gte], vars[foo][gt], vars[foo][lt], vars[foo][lte],
                    vars[foo][min], vars[foo][max]
            
            - sort
                * Sort the results based on a field
                * Allowed values: default
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
            Telerivet_APICursor (of Telerivet_ContactServiceState)
     */
    function queryContactStates($options = null)
    {
        return $this->_api->newApiCursor('Telerivet_ContactServiceState', "{$this->getBaseApiPath()}/states", $options);
    }

    /**
        $service->getContactState($contact)
        
        Gets the current state for a particular contact for this service.
        
        If the contact doesn't already have a state, this method will return
        a valid state object with id=null. However this object would not be returned by
        queryContactStates() unless it is saved with a non-null state id.
        
        Arguments:
          - $contact (Telerivet_Contact)
              * The contact whose state you want to retrieve.
              * Required
          
        Returns:
            Telerivet_ContactServiceState
     */
    function getContactState($contact)
    {
        return new Telerivet_ContactServiceState($this->_api, $this->_api->doRequest('GET', $this->getBaseApiPath() . '/states/' . $contact->id));        
    }
    
    /**
        $service->setContactState($contact, $options)
        
        Initializes or updates the current state for a particular contact for the given service. If
        the state id is null, the contact's state will be reset.
        
        Arguments:
          - $contact (Telerivet_Contact)
              * The contact whose state you want to update.
              * Required
          
          - $options (associative array)
              * Required
            
            - id (string, max 63 characters)
                * Arbitrary string representing the contact's current state for this service, e.g.
                    'q1', 'q2', etc.
                * Required
            
            - vars (associative array)
                * Custom variables stored for this contact's state
          
        Returns:
            Telerivet_ContactServiceState
     */
    function setContactState($contact, $options)
    {
        return new Telerivet_ContactServiceState($this->_api, $this->_api->doRequest('POST', $this->getBaseApiPath() . '/states/' . $contact->id, $options));
    }    

    /**
        $service->resetContactState($contact)
        
        Resets the current state for a particular contact for the given service.
        
        Arguments:
          - $contact (Telerivet_Contact)
              * The contact whose state you want to reset.
              * Required
          
        Returns:
            Telerivet_ContactServiceState
     */
    function resetContactState($contact)
    {
        return new Telerivet_ContactServiceState($this->_api, $this->_api->doRequest('DELETE', $this->getBaseApiPath() . '/states/' . $contact->id));        
    }

    /**
        $service->save()
        
        Saves any fields or custom variables that have changed for this service.
    */
    function save()
    {
        parent::save();
    }

    function getBaseApiPath()
    {
        return "/projects/{$this->project_id}/services/{$this->id}";
    }
}
