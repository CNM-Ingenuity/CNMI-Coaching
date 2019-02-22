describe('Track Coaching Hours', () => {
    const client = 'Jane Doe'
    const sessionDate = '2019-02-10'
    const minutes = '45'
    const comments = 'love this lesson'

    // test submit a form with all valid inputs by a submit button
    it('shows a success message upon submitting a valid form', () => {
        cy.visit('/login')
        cy.setCookie('wordpress_logged_in_cabf0dd96b0e4f91d4e511df188b7b4d', 'coachintraining%7C1551045902%7C7gfHHUS2T00RTYZNk9dcsUlGAkdHoC1nD0SHc1ODnnu%7Ca2f45e47a30da63e8bd99f31fae9e29fd79914c0eca3210a048d5e9bdea79522')
        cy.get('#user_login').type('matt+cit@11online.us')
        cy.get('#user_pass').type('pSc3gM0IpbicjGwarXC2NyfP')
        cy.get('#wp-submit').click()
        cy.url().should('eq', `${Cypress.config().baseUrl}/dashboard/`)

        cy.visit('/track-coaching-hours/?certification=1')
        cy.get('[label="client_name"]').type(client)
        cy.get('[label="date"]').type(sessionDate)
        cy.get('[label="minutes"]').type(minutes)
        cy.get('textarea').type(comments)
        cy.get('[type="submit"]').click()
        cy.get('.success-message', {timeout: 10000})

        cy.getByText(/^Your coaching hours have been saved.$/)
    })

    beforeEach(() => {
        cy.visit('/track-coaching-hours/?certification=1')
     })

    //each input field accepts input
    it('accepts input for name', () => { 
        cy.get('[label="client_name"]')
            .as('myElement')
            .type(client)

        cy.get('@myElement')
            .should('have.value', client)
    })

    it('accepts input for date', () => {
       cy.get('[label="date"]')
            .as('myElement')
            .type(sessionDate)

        cy.get('@myElement')    
            .should('have.value', sessionDate)
    })

    it('accepts input for minutes', () => {
        cy.get('[label="minutes"]')
            .as('myElement')
            .type(minutes)

        cy.get('@myElement')          
            .should('have.value', minutes)
    })

    it('accepts input for comments', () => {
        cy.get('textarea')
            .as('myElement')
            .type(comments)

        cy.get('@myElement')            
            .should('have.value', comments)
    })

    // test the URL remains the same after hitting submit (both success and failure)
    it('remains on the same page after form was submitted', () => {
        cy.get('[type="submit"]')
            .click()
        
        cy.url()
            .should('eq', `${Cypress.config().baseUrl}/track-coaching-hours/?certification=1`)
    })

    // test submit a form with any of the field empty
    it('shows an error if input name field is empty on enter', () => { 
        cy.get('[label="client_name"]').clear().type('{enter}')
        cy.getByText(/^This field is required.$/)
    })

    it('shows an error if input name field is empty on hitting submit', () => {
        cy.get('[label="client_name"]').clear()
        cy.get('[type="submit"]').click()
        cy.getByText(/^This field is required.$/)
    })

    it('shows an error if input date field is empty on hitting submit', () => {
        cy.get('[label="date"]').clear()
        cy.get('[type="submit"]').click()
        cy.getByText(/^This field is required.$/)
    })

    it('shows an error if input minutes field is empty on enter', () => {
        cy.get('[label="minutes"]').clear().type('{enter}')
        cy.getByText(/^This field is required.$/)
    })

    it('shows an error if input minutes field is empty on hitting submit', () => {
        cy.get('[label="minutes"]').clear()
        cy.get('[type="submit"]').click()
        cy.getByText(/^This field is required.$/)
    })

    it('shows an error if textarea field is empty on hitting submit', () => {
        cy.get('textarea').clear()
        cy.get('[type="submit"]').click()
        cy.getByText(/^This field is required.$/)
    })

    // an unauthorized user tries to submit a form
    it.only(`doesn't let a not logged in user submit a form`, () => { 
        // if a user logged in -- log out
        cy.get('#menu-main-menu > :nth-child(6) > a').click()
        cy.reload()
        cy.visit('/track-coaching-hours/?certification=1')
        cy.get('#menu-main-menu > :nth-child(6) > a').should('have.text', 'Log In')
        // fill in the form
        cy.get('[label="client_name"]').type(client)
        cy.get('[label="date"]').type(sessionDate)
        cy.get('[label="minutes"]').type(minutes)
        cy.get('textarea').type(comments)
        cy.get('[type="submit"]').click()
        cy.getByText(/^Sorry*/)
        // cy.get('.success-message', { timeout: 10000 }
    })





})