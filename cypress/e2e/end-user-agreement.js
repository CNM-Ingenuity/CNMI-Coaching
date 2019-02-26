describe('Coach End User Agreement', () => {
    function login() {
        cy.fixture('admin')
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

    it('fails to upload since no file was selected', () => {
        cy.get('div > input[type=file]')
            .click()

        cy.get('[type="submit"]')
            .click()
        cy.getByText(/^Some information is missing*/i)
    })

    
/*
    it.only('uploads a selected file', () => {
        const fileName = './fixture/sample-file.txt'
        cy.get('div > input[type=file]')
            .then(($input) => { 
                $input.setAttribute('name', fileName)
                return $input
            })

        cy.get('[type="submit"]')
            .click()
        cy.getByText(/^Your end user/i)
    })

    it.only('uploads a selected file', () => {
        const fileName = '../fixture/sample-file.txt'
        cy.get('div > input[type=file]')
            .then(($input) => {
                $input.val = fileName
                // $input.setAttribute('name', fileName)
                return $input
            })
        // .click()       

        cy.get('[type="submit"]')
            .click()
        cy.getByText(/^Your end user/i)
    })

    it.only('uploads a selected file', () => {
        const fileName = '../fixture/sample-file.txt'
        cy.get('div > input[type=file]')
            .then(($input) => {
                $input.type = 'text'
                return $input
            })
            .type(fileName)
        // .click()

        cy.get('[type="submit"]')
            .click()
        cy.getByText(/^Your end user/i)
    })
    */   
    
})
