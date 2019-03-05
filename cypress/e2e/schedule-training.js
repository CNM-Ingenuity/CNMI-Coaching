describe('Schedule Training', () => {
    function login() {
        cy.fixture('users/admin-licorg')
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
        cy.visit('/events/community/add')
    })

    it.only(`displays 'Submit an Event' main title`, () => {
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
})