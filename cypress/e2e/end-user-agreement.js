describe('Coach End User Agreement', () => {
    beforeEach(() => {
        cy.visit('/coach-end-user-agreement/?certification=1')
    })

    it('selects file for upload', () => {
        const fileName = 'fixtures/sample-file.json'
        cy.get('div > input[type="file"]')
            .click()
            .type(fileName)
            .get('[type="submit"]')
            .click()
    })
})
