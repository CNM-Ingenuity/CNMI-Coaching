describe('Track CEUs', () => {
    const inputOutside = ['5', 'Advanced C++', 'My company', 'John Smith', '2019-03-18', '2010-06-28', 'https://mywebsite.com', 'https://mywebsite.com/agenda/']
    const textOutside = ['Advanced C++ Curiculum', 'Learn C++ in depth']

    function login() {
        cy.fixture('users/admin-cc')
            .then((admin) => {
                cy.visit('/login/')
                cy.get('#user_login').type(admin.email)
                cy.get('#user_pass').type(admin.password)
                cy.get('#wp-submit').click()
            })
    }

    function getForm() {
        return cy.get('#ceu-entry-form')
    }

    function outside() {
        cy.get('#is_outside_cnm')
            .select('Yes')
    }

    function inside() {
        cy.get('#is_outside_cnm')
            .select('No')
    }

    function getElement(isOutside, childNum) {
        const currId = isOutside ? 'outside-cnm' : 'use-cnm'
        isOutside ? outside() : inside()

        return cy.get(`#${currId} > :nth-child(${childNum})`)
    }

    function getAllInputs(isOutside) {
        const currId = isOutside ? 'outside-cnm' : 'use-cnm'
        isOutside ? outside() : inside()

        return cy.get(`#${currId} input[label]`)
    }

    function getAllTextareas(isOutside) {
        const currId = isOutside ? 'outside-cnm' : 'use-cnm'
        isOutside ? outside() : inside()

        return cy.get(`#${currId} textarea`)
    }

    function getSpecificInput(isOutside, ind) {
        const currId = isOutside ? 'outside-cnm' : 'use-cnm'
        isOutside ? outside() : inside()

        return cy.get(`#${currId} input[label]:nth-of-type(${ind})`)
    }

    function getSpecificTextareas(isOutside, ind) {
        const currId = isOutside ? 'outside-cnm' : 'use-cnm'
        isOutside ? outside() : inside()

        return cy.get(`#${currId} textarea:nth-of-type(${ind})`)
    }

    function testEmptyInputs(isOutside) {
        getAllInputs(isOutside)
            .as('inputs')
            .clear()

        cy.get('[type="submit"]').click()

        cy.get('@inputs')
            .then((colection) => {
                const colLength = colection.length
                for (let i = 0; i < colLength; i++) {
                    cy.get(`#${colection[i].name}-error`)
                        .should('have.class', 'error')
                        .should('have.text', 'This field is required.')
                        .and('be.visible')
                }
            })
    }

    function testEmptyTextareas(isOutside) {
        getAllTextareas(isOutside)
            .as('textareas')
            .clear()

        cy.get('[type="submit"]').click()

        cy.get('@textareas')
            .then((colection) => {
                const colLength = colection.length
                for (let i = 0; i < colLength; i++) {
                    cy.get(`#${colection[i].name}-error`)
                        .should('have.class', 'error')
                        .should('have.text', 'This field is required.')
                        .and('be.visible')
                }
            })
    }

    function testLabel(isOutside, childNum, text, attrFor) {
        getElement(isOutside, childNum)
            .as('thisLabel')
            .contains(text)
            .should('have.prop', 'tagName')
            .and('eq', 'LABEL')

        cy.get('@thisLabel')
            .should('be.visible')
            .and('have.attr', 'for', attrFor)
    }

    function tesInput(isOutside, childNum, currLabel, currInput) {
        getElement(isOutside, childNum)
            .should('be.visible')
            .should('have.attr', 'label', currLabel)
            .and('have.attr', 'required')

        getElement(isOutside, childNum)
            .clear()
            .type(currInput)

        getElement(isOutside, childNum)
            .should('have.value', currInput)
    }

    function testSelect(isOutside, childNum, currName, options) {
        getElement(isOutside, childNum)
            .should('have.attr', 'name', currName)
            .and('have.attr', 'required')

        for (let i = 0; i < options.length; i++) {
            getElement(isOutside, childNum)
                .select(options[i][0])
                .should('have.value', options[i][1])
        }
    }

    function fillInForm(isOutside, opts, text) {
        getAllInputs(isOutside)
            .as('inputs')
            .clear()
        
        getAllTextareas(isOutside)
            .as('textareas')
            .clear()

        cy.get('[type="submit"]').click()

        for (let i = 0; i < opts.length; i++) {
            getSpecificInput(isOutside, i+1)
                .type(opts[i])
                .should('have.value', opts[i])
        }

        for (let j = 0; j < text.length; j++) {
            getSpecificTextareas(isOutside, j + 1)
                .type(text[j])
                .should('have.value', text[j])
        }
    }

    before(() => {
        login()
    })

    beforeEach(() => {
        cy.visit('/track-ceus/?certification=4')
    })

    it(`displays 'Track CEUs' title`, () => {
        cy.get('.first > h3')
            .invoke('text')
            .then((text) => {
                expect(text.trim()).to.contain('Track CEUs')
            })
    })

    it(`says 'Matthew Harris' next to the gears icon`, () => {
        cy.get('.user-name > p')
            .invoke('text')
            .then((text) => {
                expect(text.trim()).to.contain('Matthew Harris')
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

    it(`has 'CUEs Outside of CNM?' label`, () => {
        getForm()
            .find(`label:nth-of-type(1)`)
            .contains('CUEs Outside of CNM?')
            .should('be.visible')
            .and('have.attr', 'for')
            .invoke('toString')
            .then((text) => {
                expect(text.trim()).to.contain('is_outside_cnm')
            }) 
    })

    it(`has 'CUEs Outside of CNM?' select option`, () => {
        const currName = 'is_outside_cnm'
        cy.get(`#${currName}`)
            .as('thisSelect')
            .should('have.id', currName)
            .and('have.attr', 'name', currName)
        
        cy.get('@thisSelect')
            .select('Yes')
            .should('have.value', '1')
        
        cy.get('@thisSelect')
            .select('No')
            .should('have.value', '0')     
    })

    
    it(`has 'CEUs Requested' label and input`, () => {
        testLabel(true, 1, 'CEUs Requested', 'ceus_requested')
        tesInput(true, 2, 'ceus_requested', '5')
    })

    it(`has 'Certification' label and corresponding select item`, () => {
        const currOptions = [['Financial Coach', 'financial_coach'],
            ['Academic Coach', 'academic_coach'],
            ['Career Coach', 'career_coach'],
            ['Coach Trainer', 'coach_trainer']]
        
        testLabel(true, 3, 'Certification', 'certification')
        testSelect(true, 4, 'certification', currOptions)
    })

    it(`has 'Program/Training Title' label and input`, () => {
        testLabel(true, 5, 'Program/Training Title', 'program_training_title')
        tesInput(true, 6, 'program_training_title', 'My New Training')
    })

    it(`has 'Organization or Sponsor of Training' label and input`, () => {
        testLabel(true, 7, 'Organization or Sponsor of Training', 'org_sponsor')
        tesInput(true, 8, 'org_sponsor', 'My Company')
    })

    it(`has 'Trainer Name' label and input`, () => {
        testLabel(true, 9, 'Trainer Name', 'trainer_name')
        tesInput(true, 10, 'trainer_name', 'Tim George')
    })

    it.only(`has 'Start Date' label and input`, () => {
        testLabel(true, 11, 'Start Date', 'start_date')
        tesInput(true, 12, 'start_date', '2019-03-19')
    })

    it(`has 'End Date' label and input`, () => {
        testLabel(true, 13, 'End Date', 'end_date')
        tesInput(true, 14, 'end_date', '2019-06-29')
    })

    it(`has 'Program Description' label and input`, () => {
        testLabel(true, 15, 'Program Description', 'program_description')
        tesInput(true, 16, 'program_description', 'Advanced C++')
    })

    it(`has 'Program Website' label and input`, () => {
        testLabel(true, 17, 'Program Website', 'program_website')
        tesInput(true, 18, 'program_website', 'https://mywebsite.com')
    })

    it(`has 'Learning Objectives' label and input`, () => {
        testLabel(true, 19, 'Learning Objectives', 'learning_objectives')
        tesInput(true, 20, 'learning_objectives', 'Learn C++ in depth')
    })

    it(`has 'Agenda Url' label and input`, () => {
        testLabel(true, 21, 'Agenda Url', 'agenda_url')
        tesInput(true, 22, 'agenda_url', 'https://mywebsite.com/agenda/')
    })
    
    it(`shows an error message below each of input fields that is empty upon hitting the 'Submit' button for outside CNM`, () => {
        testEmptyInputs(true)
        testEmptyTextareas(true)
    })

    it.only(`allows submitting the form for outside CNM if all input fields are filled out`, () => {
        fillInForm(true, inputOutside, textOutside)
        cy.get('[type="submit"]').click()

        cy.getByText(/^Your CEU request has been saved.$/i)
            .should('be.visible')
            .should('have.class', 'success-message')
            .should('have.prop', 'tagName')
            .and('eq', 'P')
    })

    

    








    //test 'Inside of CNM' option
    
    it(`shows an error message below each of input fields that is empty upon hitting the 'Submit' button for inside CNM`, () => {
        testEmptyInputs(false)
    })





    /*

     it('shows an error if input name field is empty on enter', () => {
        cy.get('[label="client_name"]').clear().type('{enter}')
        cy.getByText(/^This field is required.$/)
            .should('be.visible')
    })

    */
})