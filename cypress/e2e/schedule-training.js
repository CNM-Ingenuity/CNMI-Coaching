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


    before(() => {
        login()
    })

    beforeEach(() => {
        cy.visit('/events/community/add')
    })

    it(`displays 'Submit an Event' main title`, () => {
        cy.get('h1.entry-title')
            .invoke('text')
            .then((text) => {
                expect(text.trim()).to.contain('Submit an Event')
            })
    })

    it(`displays 'Schedule a Training' title`, () => {
        cy.get('.first > h3')
            .invoke('text')
            .then((text) => {
                expect(text.trim()).to.contain('Schedule a Training')
            })
    })

    it(`says 'Matt Harris' next to the gears icon`, () => {
        cy.get('.user-name > p')
            .invoke('text')
            .then((text) => {
                expect(text.trim()).to.contain('Matt Harris')
            })
    })

    it(`redirects to dashboard upon clickin on gears icon`, () => {
        cy.get('.user-name > p > a')
            .as('myLink')
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
        cy.get('.tribe-section-header > h3')
            .should('be.visible')
            .invoke('text')
            .then((text) => {
                expect(text.trim()).to.contain('Event Time & Date')
            })
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
        cy.get('#event-timezone')
            .should('have.attr', 'name', 'EventTimezone')
            .should('have.attr', 'data-timezone-label', 'Timezone:')
            .should('have.attr', 'style', 'display: none;')
            .and('have.attr', 'data-timezone-value')
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
        cy.getByText(/^Event Image$/i)
            .should('be.visible')
            .should('have.prop', 'tagName')
            .and('eq', 'H3')
    })

    it.only(`has image upload area`, () => {
        cy.get('.note > p')
            .should('be.visible')
            .invoke('text')
            .then((text) => {
                expect(text.trim()).to.contain('Choose a .jpg, .png, or .gif file under 50 MB in size.')
            })
    })






    









})