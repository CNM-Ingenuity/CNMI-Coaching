describe('Submit an Event', () => {
    function login() {
        cy.fixture('users/admin-licorg')
            .then((admin) => {
                cy.visit('/login/')
                cy.get('#user_login').type(admin.email)
                cy.get('#user_pass').type(admin.password)
                cy.get('#wp-submit').click()
            })
    }

    function getToday() {
        const today = new Date()
        let dd = today.getDate()
        let mm = today.getMonth() + 1
        const yyyy = today.getFullYear()

        if (dd < 10) {
            dd = '0' + dd
        }

        if (mm < 10) {
            mm = '0' + mm
        }

        return `${yyyy}-${mm}-${dd}`
    }

    function testLabel(selector, attrFor, title, isRequired, isScreenReader) {
        cy.get(selector)
            .as('thisLabel')
            .contains(title)
            .should('have.prop', 'tagName')
            .and('eq', 'LABEL')

        cy.get('@thisLabel')
            .should('be.visible')
            .and('have.attr', 'for', attrFor)

        if (isRequired) {
            cy.get(`${selector} > span`)
                .should('have.class', 'req')
                .and('have.text', '(required)')
        }

        if (isScreenReader) {
            cy.get('@thisLabel')
                .should('have.class', 'screen-reader-text')
        }
    }

    function testInput(inputId, currInput) {
        cy.get(`#${inputId}`)
            .should('be.visible')
            .should('have.attr', 'name', inputId)
            .should('have.attr', 'type', 'text')
            .and('have.attr', 'value')

        cy.get(`#${inputId}`)
            .clear()
            .type(currInput)

        cy.get(`#${inputId}`)
            .should('have.value', currInput)
    }

    function tesTextarea(textareaId, currName, currText) {
        cy.get(`#${textareaId}`)
            .should('be.visible')
            .and('have.attr', 'name', currName)

        cy.get(`#${textareaId}`)
            .clear()
            .type(currText)

        cy.get(`#${textareaId}`)
            .should('have.value', currText)
    }

    function testText(selector, textContent, tag) {
        cy.get(selector)
            .contains(textContent)
            .should('be.visible')
            .should('have.prop', 'tagName')
            .and('eq', tag)           
    }

    function testSpecialElement(selector, isVisible, arr) {
        cy.get(selector)

        isVisible ? cy.should('be.visible') : cy.should('not.be.visible')

        for (let i = 0; i < arr.length; i++) {
            (i === arr.length - 1) ? cy.and('have.attr', arr[i][0], arr[i][1]) : cy.should('have.attr', arr[i][0], arr[i][1])
        }
    }

    function fillInRequired(selectors, values) {
        const len = selectors.length

        for (let i = 0; i < len; i++) {
            cy.get(selectors[i])
                .clear()
                .type(values[i])
                .should('have.value', values[i])
        }
    }

    before(() => {
        login()
    })

    beforeEach(() => {
        cy.visit('/events/community/add')
    })

    it(`displays 'Submit an Event' main title`, () => {
        testText('h1.entry-title', 'Submit an Event', 'H1')
    })

    it(`displays 'Schedule a Training' title`, () => {
        testText('.first > h3', 'Schedule a Training', 'H3')
    })

    // depends on the logged in user
    it(`says 'Matt Harris' next to the gears icon`, () => {
        testText('.user-name > p', 'Matt Harris', 'P')
    })

    it(`redirects to dashboard upon clicking on gears icon`, () => {
        cy.get('.user-name > p > a')
            .should('have.attr', 'href')
            .then((href) => {
                cy.visit(href)
                    .url()
                    .should('eq', `${Cypress.config().baseUrl}${href}/`)
            })
    })

    it(`has 'Event Title:' label and corresponding input field`, () => {
        const forAttr = 'post_title'
        testLabel(`.events-community-post-title > label[for=${forAttr}]`, forAttr, 'Event Title:', true, false) 
        testInput(forAttr, 'Community Get-together')
    })

    it(`has 'EVENT DESCRIPTION:' label and corresponding textarea field`, () => {
        const forAttr = 'post_content'
        testLabel(`.events-community-post-content > label[for=${forAttr}]`, forAttr, 'Event Description:', true, false)
        tesTextarea(forAttr, 'tcepostcontent', 'This is a wonderful event')
    })

    it(`has 'EVENT TIME & DATE' section`, () => {
        testText('.tribe-section-header > h3', 'Event Time & Date', 'H3')
    })

    it(`has 'Start/End:' label`, () => {
        testLabel('.tribe-section-content-label > label', 'EventStartDate', 'Start/End:', false, false)
    })

    it(`has 'Event Series:' label`, () => {
        testLabel('td.recurrence-rules-header > label', 'EventSeries', 'Event Series:', false, false)
    })

    it(`has 'Start/End' labels and input fields`, () => {
        testLabel('.tribe-section-content-field > label:nth-of-type(1)', 'EventStartDate', 'Event Start Date', false, true)
        testLabel('.tribe-section-content-field > label:nth-of-type(2)', 'EventStartTime', 'Event Start Time', false, true)
        testLabel('.tribe-section-content-field > label:nth-of-type(3)', 'EventEndTime', 'Event End Time', false, true)
        testLabel('.tribe-section-content-field > label:nth-of-type(4)', 'EventEndDate', 'Event End Date', false, true)

        testInput('EventStartDate', '2019-03-31')
        testInput('EventStartTime', '09:00:00')
        testInput('EventEndTime', '18:00:00')
        testInput('EventEndDate', '2019-04-30')   
    })

    it(`has 'Change Timezone' link`, () => {
        cy.get('.tribe-change-timezone')
            .should('have.attr', 'href', '#')
            .should('have.prop', 'tagName')
            .and('eq', 'A')
    })

    it(`has 'Event Timezone' select element`, () => {
        const arr = [['name', 'EventTimezone'], ['data-timezone-label', 'Timezone:'], ['style', 'display: none;']]

        testSpecialElement('#event-timezone', false, arr)
    })

    it(`has 'Schedule multiple events' button`, () => {
        cy.get('#tribe-add-recurrence')
            .should('have.prop', 'tagName')
            .and('eq', 'BUTTON')

        cy.get('#tribe-add-recurrence')
            .contains('Schedule multiple events')
            .should('have.prop', 'tagName')
            .and('eq', 'SPAN')
        
        cy.get('#tribe-add-recurrence')
            .contains('Add more events')
            .should('have.prop', 'tagName')
            .and('eq', 'SPAN')
    })

    it(`has 'EVENT IMAGE' title`, () => {
        testText('.tribe-section-image-uploader > .tribe-section-header > h3', 'Event Image', 'H3')
    })

    it(`has an image upload area`, () => {
        const arr = [['type', 'file'], ['name', 'event_image'], ['class', 'EventImage']]

        testText('.note > p', 'Choose a .jpg, .png, or .gif file under 50 MB in size.', 'P')       
        testLabel('.form-controls > label:nth-of-type(2)', 'EventImage', 'Event Image', false, true)
        testLabel('.form-controls > label:nth-of-type(3)', 'uploadFile', 'Upload File', false, true)
        testSpecialElement('#EventImage', true, arr)
    })

    it(`has 'TRAINING TYPE' label and corresponding dropdown item`, () => {
        const arr1 = [['type', 'text'], ['aria-haspopup', 'true'], ['role', 'button']]
        const arr2 = [['name', 'tax_input[tribe_events_cat]'], ['placeholder', 'Search from existing categories'], ['data-source', 'search_terms']]

        testText(':nth-child(8) > .tribe-section-header > h3', 'Training Type', 'H3')
        testText(':nth-child(8) > .tribe-section-header > span', '(required)', 'SPAN')

        cy.get('#s2id_autogen3 > a.select2-choice.select2-default')
            .should('have.attr', 'href')
        
        testText('#select2-chosen-4', 'Search from existing categories', 'SPAN')
        testSpecialElement('#s2id_autogen4', true, arr1)
        testSpecialElement('input.tribe-dropdown.tribe-dropdown-created', false, arr2)
    })

    it(`has 'COACHES FOR THIS EVENT' label and corresponding input field`, () => {
        const arr = [['type', 'text'], ['autocomplete', 'off'], ['autocorrect', 'off'], ['autocapitalize', 'off'], ['placeholder', '']]

        testText(':nth-child(9) > .tribe-section-header > h3', 'Coaches for this Event', 'H3')
        testText(':nth-child(9) > .tribe-section-header > span', '(required)', 'SPAN')
        testSpecialElement('#s2id_autogen1', true, arr)
    })

    it(`has 'VENUE DETAILS' label and corresponding input field`, () => {
        const arr = [['type', 'text'], ['aria-haspopup', 'true'], ['role', 'button']]

        testText('#event_tribe_venue > .tribe-section-header > h3', 'Venue Details', 'H3')
        testText('.saved-venue-table-cell > label', 'Venue:', 'LABEL')
        testSpecialElement('.select2-container.tribe-dropdown > input.select2-focusser.select2-offscreen', true, arr)

        cy.get('#event_tribe_venue .select2-container.linked-post-dropdown > a.select2-choice')
            .should('be.visible')
            .should('have.attr', 'href')
    })

    it(`has 'ORGANIZER DETAILS' label and corresponding items`, () => {
        const arr = [['type', 'text'], ['aria-haspopup', 'true'], ['role', 'button'], ['aria-labelledby', 'select2-chosen-6']]
        
        testText('#event_tribe_organizer > .tribe-section-header > h3', 'Organizer Details', 'H3')
        testText('.saved-organizer-table-cell > label', 'Organizer:', 'LABEL')
        testSpecialElement('#s2id_autogen6', true, arr)

        cy.get('#event_tribe_organizer .select2-container.tribe-dropdown > a.select2-choice')
            .should('be.visible')
            .should('have.attr', 'href')
        
        testText('#select2-chosen-6', 'Create or Find an Organizer', 'SPAN')

        cy.get('#event_tribe_organizer a.dashicons.dashicons-trash.tribe-delete-this')
            .as('myLink')
            .should('be.visible')
            .and('have.attr', 'href', '#')
        
        cy.get('@myLink')
            .find('span')
            .should('have.class', 'screen-reader-text')
            .and('have.text', 'Delete this')

        cy.get('#event_tribe_organizer a.tribe-add-post.tribe-button.tribe-button-secondary')
            .should('be.visible')
            .should('have.attr', 'href', '#')
            .and('have.text', 'Add another organizer')

    })

    it(`has 'EVENT WEBSITE' label and corresponding items`, () => {
        const arr = [['type', 'text'], ['name', 'EventURL'], ['placeholder', 'Enter URL for event information'], ['size', '25']]

        testText('.tribe-section-website > .tribe-section-header > h3', 'Event Website', 'H3')
        testText('tr.tribe-section-content-row > td.tribe-section-content-label > label[for="EventURL"]', 'External Link:', 'LABEL')
        testSpecialElement('#EventURL', true, arr)
    })

    it(`has 'EVENT COST' label and corresponding items`, () => {
        const arr = [['type', 'text'], ['name', 'EventCost'], ['class', 'cost-input-field'], ['size', '6']]

        testText('.tribe-section-cost > .tribe-section-header > h3', 'Event Cost', 'H3')
        testText('tr.tribe-section-content-row > td.tribe-section-content-label > label[for="EventCost"]', 'Cost:', 'LABEL')
        testSpecialElement('#EventCost', true, arr)
        testInput('EventCost', '$100.00')
        testText('.tribe-section-content-field > p', 'Leave blank to hide the field. Enter a 0 for events that are free.', 'P')
    })

    /*
    it.only(`has 'Add New Event' label and corresponding items upon successful event submision`, () => {
        const selectors = ['#post_title', '#post_content']
        const values = ['React Meetup', 'Discussions about ReactJS']

        fillInRequired(selectors, values)

     })
     */
})