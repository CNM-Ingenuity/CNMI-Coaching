describe('Coach End User Agreement', () => {
    function login() {
        cy.fixture('users/admin-cit')
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
        cy.visit('/coach-end-user-agreement/?certification=1')
    })

    it(`displays 'Coach End User Agreement'`, () => {
        cy.get('.first > h3')
            .invoke('text')
            .then((text) => {
                expect(text.trim()).to.contain('Coach End User Agreement')
            })
    })

    it(`has 'In Training' sign`, () => {
        cy.get('.user-name > p')
            .invoke('text')
            .then((text) => {
                expect(text.trim()).to.contain('In Training')
            })
    })

    it(`redirects to dashboard upon clickin on gears icon`, () => {
        cy.get('.user-name > p > a > .dashicons')
            .click()
        cy.url()
            .should('eq', `${Cypress.config().baseUrl}/dashboard/`)
    })

    it('fails to upload since no file was selected', () => {
        cy.get('div > input[type=file]')
            .click()

        cy.get('[type="submit"]')
            .click()
        cy.getByText(/^Some information is missing*/i)
            .should('be.visible')
    })   
})
