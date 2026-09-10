describe('Inscription frontend', () => {
  beforeEach(() => {
    cy.visit('http://localhost:3000'); // ton frontend
  });

  it('inscrit un utilisateur avec succès', () => {
    cy.get('input[name="nom"]').type('CIUser');
    cy.get('input[name="email"]').type('ciuser@example.com');
    cy.get('form').submit();

    cy.contains('Inscription réussie').should('exist');
    cy.get('#inscriptions').contains('CIUser').should('exist');
  });

  it('refuse un doublon d’email', () => {
    cy.get('input[name="nom"]').type('CIUser');
    cy.get('input[name="email"]').type('ciuser@example.com');
    cy.get('form').submit();

    cy.contains('déjà inscrite').should('exist');
  });

  it('refuse si champs vides', () => {
    cy.get('form').submit();
    cy.contains('Veuillez remplir tous les champs.').should('exist');
  });
});
