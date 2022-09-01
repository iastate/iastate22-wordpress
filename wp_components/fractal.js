"use strict";

const fs = require("fs");
const path = require("path");
const _kebabCase = require("lodash.kebabcase");
const twigAdapter = require("@frctl/twig")({
  importContext: true,
});

/* Create a new Fractal instance and export it for use elsewhere if required */
const fractal = (module.exports = require("@frctl/fractal").create());

/* Set components engine to twig */
fractal.components.engine(twigAdapter);

/* Set the file extension for components */
fractal.components.set("ext", ".twig");

/* Set the title of the project */
fractal.set("project.title", "Component Library");

/* Tell Fractal where the components will live */
fractal.components.set("path", __dirname + "/src/components");

/* Tell Fractal where the documentation pages will live */
fractal.docs.set("path", __dirname + "/src/docs");

/* Tell Fractal where the static assets will live */
fractal.web.set("static.path", __dirname + "/build");

/* Tell Fractal where the static HTML web UI export lives */
fractal.web.set("builder.dest", __dirname + "/fractal");

/* Add a custom command to the Fractal CLI to quickly scaffold components */
fractal.cli.command("create-component <componentName>", (args, done) => {
  const componentsRoot = fractal.components.get("path");
  const componentExt = fractal.components.get("ext");
  const componentName = _kebabCase(args.componentName);
  const componentPath = path.resolve(componentsRoot, componentName);

  console.log(`Creating component directory for "${args.componentName}"...`);
  fs.mkdirSync(componentPath);
  console.log(`Creating component template for "${args.componentName}"...`);
  fs.writeFileSync(path.resolve(componentPath, `${componentName}${componentExt}`), "");
  console.log(`Creating config.json for "${args.componentName}"...`);
  fs.writeFileSync(
    path.resolve(componentPath, `${componentName}.config.json`),
    JSON.stringify({ context: {} }, null, "\t")
  );
  console.log(`Creating component stylesheet for "${args.componentName}"...`);
  fs.writeFileSync(path.resolve(componentPath, `_${componentName}.scss`), "");
  console.log(`Finished scaffolding "${args.componentName}"!`);

  done();
});
