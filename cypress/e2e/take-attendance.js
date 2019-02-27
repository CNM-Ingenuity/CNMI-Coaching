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

    it(`has 'Certified Trainer' sign`, () => {
        cy.get('.user-name > p')
            .invoke('text')
            .then((text) => {
                expect(text.trim()).to.contain('Certified Trainer')
            })
    })

    it(`redirects to dashboard upon clickin on gears icon`, () => {
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
                console.log(eventNum)

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
        const sessionNum = '4'
        cy.get('select')
            .select(`Session ${sessionNum}`)
            .should('have.value', sessionNum)
    })
  
})