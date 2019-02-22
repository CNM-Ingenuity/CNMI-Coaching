describe('Track Coaching Hours', () => {
    // test the form itself including the submit button
    it('can track coaching hours', () => {
        const client = 'Jane Doe'
        const sessionDate = '2019-02-10'
        const minutes = '45'
        const comments = 'love this lesson'

        // first a user needs to be logged in



        cy
            .visit('/track-coaching-hours/?certification=1')
            .get('[label="client_name"]')
            .click()
            .type(client)
            .get('[label="date"]')
            .click()
            .type(sessionDate)
            .get('[label="minutes"]')
            .click()
            .type(minutes)
            .get('textarea')
            .click()
            .type(comments)
            .get('[type="submit"]')
            .click()
            .url()
            .should('eq', `${Cypress.config().baseUrl}/track-coaching-hours/?certification=1`)
    })  

})