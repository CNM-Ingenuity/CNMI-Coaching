describe('Track Coaching Hours', () => {
    beforeEach(() => {
        cy.visit('/track-coaching-hours/?certification=1')
     })

    //each input field accepts input
    it('accepts input for name', () => { 
        const client = 'Jane Doe'

        cy.get('[label="client_name"]')
            .click()
            .type(client)
            .should('have.value', client)
    })

    it('accepts input for date', () => {
        const sessionDate = '2019-02-10'

        cy.get('[label="date"]')
            .click()
            .type(sessionDate)
            .should('have.value', sessionDate)
    })

    it('accepts input for minutes', () => {
        const minutes = '45'

        cy.get('[label="minutes"]')
            .click()
            .type(minutes)
            .should('have.value', minutes)
    })

    it('accepts input for comments', () => {
        const comments = 'love this lesson'

        cy.get('textarea')
            .click()
            .type(comments)
            .should('have.value', comments)
    })


})