
import json

from transformers import pipeline

# Modelo multilíngue para sentimento (funciona bem em português)
classifier = pipeline('sentiment-analysis', model='nlptown/bert-base-multilingual-uncased-sentiment')

def nota_resposta(texto):
    # O modelo retorna labels: '1 star', ..., '5 stars'
    result = classifier(texto)[0]
    label = result['label']
    # Extrai o número de estrelas
    estrelas = int(label[0])
    # Converte para nota de 0 a 100
    return int((estrelas - 1) * 25)

def analisar_candidatos(candidatos, vaga):
    resultados = []
    skills_vaga = set(vaga.get('skills', []))
    for cand in candidatos:
        # 1. Compatibilidade de skills (máx 30)
        skills_cand = set(cand.get('skills', []))
        skills_match = len(skills_vaga & skills_cand)
        skills_score = (skills_match / max(len(skills_vaga), 1)) * 30 if skills_vaga else 0

        # 2. Experiência (máx 20)
        exp_score = min(cand.get('experiencia', 0) * 2, 20)

        # 3. Qualidade das respostas (máx 40)
        respostas = cand.get('respostas', [])
        notas = []
        for r in respostas:
            texto = r.get('resposta', '')
            nota = nota_resposta(texto)
            r['nota'] = nota
            notas.append(nota)
        media_nota = sum(notas) / max(len(notas), 1) if notas else 0
        form_score = (media_nota / 100) * 40 if notas else 0
        form_score = min(form_score, 40)

        # 4. Perfil (máx 10)
        perfil_score = 0
        perfil_score += 5 if cand.get('foto') else 0
        perfil_score += 5 if cand.get('perfil_completo') else 0

        total = round(min(skills_score + exp_score + form_score + perfil_score, 100), 1)
        resultados.append({
            'nome': cand.get('nome'),
            'skills_match': skills_match,
            'score': total,
            'explicacao': f"Skills: {skills_score:.1f}, Exp: {exp_score:.1f}, Form: {form_score:.1f}, Perfil: {perfil_score:.1f}",
            'respostas': respostas
        })
    return resultados

if __name__ == "__main__":
    import sys
    entrada = sys.stdin.read()
    dados = json.loads(entrada)
    candidatos = dados['candidatos']
    vaga = dados['vaga']
    resultado = analisar_candidatos(candidatos, vaga)
    print(json.dumps(resultado, ensure_ascii=False))
