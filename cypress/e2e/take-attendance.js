describe('Take Attendance', () => {
    function login() {
        cy.fixture('users/admin-cct')
            .then((admin) => {
                cy.visit('/login/')
                cy.get('#user_login').type(admin.email)
                cy.get('#user_pass').type(admin.password)
                cy.get('#wp-submit').click()
            })
    }

    before(() => {
        login()
    })

    beforeEach(() => {
        cy.visit('/attendance/?eventID=590')
    })

    it(`displays 'Take Attenance' title`, () => {
        cy.get('.first > h3')
            .invoke('text')
            .then((text) => {
                expect(text.trim()).to.contain('Take Attendance')
            })
    })

    // depends on the logged in user
    it(`has 'Certified Trainer' sign`, () => {
        cy.get('.user-name > p')
            .invoke('text')
            .then((text) => {
                expect(text.trim()).to.contain('Certified Trainer')
            })
    })

    it(`redirects to dashboard upon clicking on gears icon`, () => {
        cy.get('.user-name > p > a > .dashicons')
            .click()
        cy.url()
            .should('eq', `${Cypress.config().baseUrl}/dashboard/`)
    })

    it(`has form 'attendance-form' on the page with hidden input field with correct event ID value`, () => {
        cy.url()
            .invoke('toString')
            .then((text) => {
                const words = text.split('=');
                const eventNum = words[words.length - 1]

                cy.get('form#attendance-form > input[name="event_id"]')
                    .should('have.value', eventNum)
            })
    })

    it(`has required dropdown select element`, () => {
        cy.get('select')
            .should('have.attr', 'required')
    })

    it(`upon loading select element has value '1'`, () => {
        cy.get('select')
            .should('have.value', '1')
    })

    it(`allows selecting any of ten sessions`, () => {
        const sessions = ['Session 1, Day 1, Morning', 'Session 2, Day 1, Afternoon', 'Session 3, Day 2, Morning', 'Session 4, Day 2, Afternoon', 'Session 5, Day 3, Morning', 'Session 6, Day 4, Morning', 'Session 7, Day 4, Afternoon', 'Session 8, Day 5, Morning', 'Session 9, Day 5, Afternoon', 'Session 10, Day 7, Morning']

        for (let i = 0; i < sessions.length; i++) {
            cy.get('select')
                .select(sessions[i])
                .should('have.value', (i+1).toString())
        }
    })

    it(`has 'Students In Attendance' section with at least one checkbox that can be checked`, () => {
        cy.get('fieldset > legend')
            .invoke('text')
            .then((text) => {
                    expect(text.trim()).to.contain('Students In Attendance')
            })
        
        cy.get('fieldset > input[type="checkbox"]:first')
            .should('have.value', '21')
            .check()
            .should('be.checked')
    })

    it(`allows checking all four checkboxes`, () => {
        cy.get('fieldset > input[type="checkbox"]')
            .check(['21', '22', '29', '32'])
            .should('be.checked')
            .and('have.length', 4)
    })

    it(`allows saving the attendance for 'Session 2' when at least one checkbox is checked`, () => { 
        cy.get('select')
            .select(`Session 2, Day 1, Afternoon`)
        
        cy.get('fieldset > input[type="checkbox"]:nth-of-type(4)')
            .check({force: true})
            .should('be.checked')
            .and('have.value', '32')
        
        cy.get('[type="submit"]')
            .click()

        cy.getByText(/^Attendance has been saved.$/i)
            .should('be.visible')
            .and('have.css', 'background')
    })

    it(`allows saving the attendance for 'Session 6' when all four checkboxes are checked`, () => {
        cy.get('select')
            .select(`Session 6, Day 4, Morning`)
        
        cy.get('fieldset > input[type="checkbox"]')
            .check({ force: true })
            .should('be.checked')
            .and('have.length', 4)
       
        cy.get('[type="submit"]')
            .click()

        cy.getByText(/^Attendance has been saved.$/i)
            .should('be.visible')
            .and('have.css', 'background')
    })

    it(`displays an error message when trying to submit a form with all checkboxes unchecked`, () => {
        cy.get('fieldset > input[type="checkbox"]')
            .should('not.be.checked')
            .and('have.length', 4)
        
        cy.get('[type="submit"]')
            .click()
        
        cy.getByText(/^Some information is missing, please make sure your form is complete.$/i)
            .should('be.visible')
            .and('have.css', 'background')
    })

    it(`shows that it's impossible to submit a form being logged out`, () => {
        cy.get('#menu-main-menu > :nth-child(6) > a').click()
        cy.reload()
        cy.visit('/attendance/?eventID=590')
    
        cy.get('#menu-main-menu > :nth-child(6) > a')
            .should('have.text', 'Log In')
        
        cy.get('[type="submit"]')
        .should('not.exist')
    })
})