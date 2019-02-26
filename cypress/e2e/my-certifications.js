describe('My Certifications', () => {
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
        cy.visit('/my-certifications/')
    })

    it(`has 'In Training' sign`, () => {
        cy.get('.user-name > p')
            .invoke('text')
            .then((text) => { 
                expect(text.trim()).to.contain('In Training')
            })
    })

    it(`redirects to dashboard upon clickin on gears icon`, () => {
        cy.get('a[href="/dashboard"] > span')
            .click()
        cy.url()
            .should('eq', `${Cypress.config().baseUrl}/dashboard/`)
    })

    it(`each 'Academic Coach Training' box should refer to the corresponding page`, () => {
        cy.get('a[href^="/my-certification?"]')
            .should('have.attr', 'href')
            .then(($href) => { 
                cy.visit(`${Cypress.config().baseUrl}${$href}`)
                cy.get('.entry-content > div.top-matter.wrap h3')
                    .invoke('text')
                    .then((text) => {
                        expect(text.trim()).to.contain('Academic Coach Training')
                    })
            })
    })

    it(`each 'Academic Coach Training' box upon clicking anywhere on it redirects to the corresponding page`, () => {
        cy.get('a[href^="/my-certification?"]')
            .should('have.attr', 'href')
            .invoke('toString')
            .then((text) => {
                cy.get(`a[href="${text}"] > .item`)
                    .click({ multiple: true, force: true })

                cy.url()
                    .invoke('toString')
                    .then((text2) => {
                        const words = text.split('?');
                        expect(text2).to.be.equal(`${Cypress.config().baseUrl}${words[0]}/?${words[1]}`)
                    })
            })    
    })

})